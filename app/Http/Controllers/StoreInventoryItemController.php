<?php
namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StoreInventory;
use App\Models\StoreInventoryItem;
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

        // Récupérer les paramètres de filtrage
        $selectedInventoryId = $request->input('inventory_id');
        $selectedStatus = $request->input('status');

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

        $items = $items->get();

        // Passer les données à la vue
        return view('StoreInventoryItem.index', compact('items', 'inventories', 'statuses', 'selectedInventoryId', 'selectedStatus'));
    }

    public function export(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $selectedInventoryId = $request->input('inventory_id');
        $selectedStatus = $request->input('status');

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

        $items = $items->get();

        // Exporter les données vers Excel
        return Excel::download(new StoreInventoryItemsExport($items), 'store_inventory_items.xlsx');
    }
}