<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StoreInventory;
use App\Models\StoreInventoryItem;
use App\Models\Store; // Assurez-vous d'importer le modèle Store
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StoreInventoryItemsExport;

class StoreInventoryItemController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les inventaires de type "all"
        $inventories = Inventory::where('type', 'all')->get();

        // Récupérer les statuts distincts de la table store_inventories
        $statuses = StoreInventory::distinct('status')->pluck('status');

        // Récupérer la liste des magasins
        $stores = Store::all(); // Assurez-vous que le modèle Store existe

        // Récupérer les paramètres de filtrage
        $selectedInventoryId = $request->input('inventory_id');
        $selectedStatus = $request->input('status');
        $selectedStoreId = $request->input('store_id');
        $selectedDate = $request->input('date');

        // Filtrer les éléments d'inventaire
        $items = StoreInventoryItem::query();

        if ($selectedInventoryId) {
            $items->whereHas('storeInventory', function ($query) use ($selectedInventoryId) {
                $query->where('inventory_id', $selectedInventoryId);
            });
        } else {
            $items->whereHas('storeInventory.inventory', function ($query) {
                $query->where('type', 'all');
            });
        }

        if ($selectedStatus) {
            $items->whereHas('storeInventory', function ($query) use ($selectedStatus) {
                $query->where('status', $selectedStatus);
            });
        }

        if ($selectedStoreId) {
            $items->whereHas('storeInventory.store', function ($query) use ($selectedStoreId) {
                $query->where('id', $selectedStoreId);
            });
        }

        if ($selectedDate) {
            $items->whereDate('created_at', $selectedDate);
        }

        $items = $items->get();

        // Passer les données à la vue
        return view('StoreInventoryItem.index', compact(
            'items',
            'inventories',
            'statuses',
            'stores',
            'selectedInventoryId',
            'selectedStatus',
            'selectedStoreId',
            'selectedDate'
        ));
    }

    public function export(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $selectedInventoryId = $request->input('inventory_id');
        $selectedStatus = $request->input('status');
        $selectedStoreId = $request->input('store_id');
        $selectedDate = $request->input('date');
    
        // Appliquer les filtres sur la requête
        $items = StoreInventoryItem::query()
            ->when($selectedInventoryId, function ($query, $selectedInventoryId) {
                return $query->whereHas('storeInventory.inventory', function ($q) use ($selectedInventoryId) {
                    $q->where('id', $selectedInventoryId);
                });
            })
            ->when($selectedStatus, function ($query, $selectedStatus) {
                return $query->where('status', $selectedStatus);
            })
            ->when($selectedStoreId, function ($query, $selectedStoreId) {
                return $query->whereHas('storeInventory.store', function ($q) use ($selectedStoreId) {
                    $q->where('id', $selectedStoreId);
                });
            })
            ->when($selectedDate, function ($query, $selectedDate) {
                return $query->whereDate('created_at', $selectedDate);
            })
            ->with(['storeInventory.inventory', 'storeInventory.store'])
            ->get();
    
        // Passer les données filtrées à la classe d'exportation
        return Excel::download(new StoreInventoryItemsExport($items), 'store_inventory_items.xlsx');
    }
}