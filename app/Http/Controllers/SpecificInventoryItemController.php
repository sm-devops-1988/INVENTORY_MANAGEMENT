<?php
 
namespace App\Http\Controllers;
 
use App\Models\SpecificInventory;
use App\Models\StoreInventory;
use App\Models\Store;
use App\Models\Inventory; // Ajouter cette ligne
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SpecificInventoryItemsExport;
 
class SpecificInventoryItemController extends Controller
{
    /**
     * Afficher la liste des inventaires spécifiques avec les filtres.
     */
    public function index(Request $request)
    {
        // Initialiser les filtres avec des valeurs par défaut (null ou autre)
        $selectedInventoryId = $request->get('inventory_id', null);
        $selectedStatus = $request->get('status', null);
        $selectedStoreId = $request->get('store_id', null);
        $selectedDate = $request->get('date', null);
 
        // Requête pour filtrer les inventories
        $specificInventoriesQuery = SpecificInventory::with(['storeInventory', 'store'])
            ->whereHas('storeInventory', function ($query) {
                // Filtrer uniquement les inventaires de type 'specific'
                $query->whereHas('inventory', function ($query) {
                    $query->where('type', 'specific');
                });
            });
 
        // Appliquer les filtres
        if ($selectedInventoryId) {
            $specificInventoriesQuery->whereHas('storeInventory', function ($query) use ($selectedInventoryId) {
                $query->where('inventory_id', $selectedInventoryId);
            });
        }
 
        if ($selectedStatus) {
            $specificInventoriesQuery->whereHas('storeInventory', function ($query) use ($selectedStatus) {
                $query->where('status', $selectedStatus);
            });
        }
 
        if ($selectedStoreId) {
            $specificInventoriesQuery->whereHas('storeInventory', function ($query) use ($selectedStoreId) {
                $query->where('store_id', $selectedStoreId);
            });
        }
 
        if ($selectedDate) {
            $specificInventoriesQuery->whereDate('created_at', $selectedDate);
        }
 
        // Exécuter la requête
        $specificInventories = $specificInventoriesQuery->get();
 
        // Récupérer les autres données nécessaires
        $inventories = Inventory::where('type', 'specific')->get(); // Filtrer les inventaires de type 'specific'
        $stores = Store::all();
 
        // Renvoyer à la vue avec les filtres
        return view('specific_inventory.index', compact('specificInventories', 'inventories', 'stores', 'selectedInventoryId', 'selectedStatus', 'selectedStoreId', 'selectedDate'));
    }
 
    /**
     * Importer un inventaire spécifique.
     */
    public function importSpecificInventory(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'store_id' => 'required|exists:stores,id',
            'product_name' => 'required|string',
            'product_code' => 'required|string',
            'Onhand' => 'required|integer',
            'count_1' => 'nullable|integer',
            'count_2' => 'nullable|integer',
        ]);
 
        // Récupérer ou créer un StoreInventory
        $storeInventory = StoreInventory::firstOrCreate([
            'inventory_id' => $request->inventory_id,
            'store_id' => $request->store_id,
        ]);
 
        // Créer un enregistrement dans specificinventory
        $specificInventory = SpecificInventory::create([
            'product_name' => $request->product_name,
            'product_code' => $request->product_code,
            'Onhand' => $request->Onhand,
            'count_1' => $request->count_1,
            'count_2' => $request->count_2,
            'inventory_id' => $request->inventory_id,
            'store_id' => $request->store_id,
            'store_inventory_id' => $storeInventory->id,
        ]);
 
        return response()->json($specificInventory, 201);
    }
 
    /**
     * Exporter les données en fichier Excel.
     */
    public function export(Request $request)
{
    $selectedInventoryId = $request->input('inventory_id');
    $selectedStoreId = $request->input('store_id');
    $selectedDate = $request->input('date');
    $selectedStatus = $request->input('status');

    // Filtrer les données avant exportation
    $items = SpecificInventory::query()
        ->join('store_inventories', 'store_inventories.id', '=', 'specificinventory.store_inventory_id')
        ->join('inventories', 'inventories.id', '=', 'store_inventories.inventory_id') // Joindre la table inventory
        ->join('stores', 'stores.id', '=', 'store_inventories.store_id') // Joindre la table stores
        ->select(
            'specificinventory.id',
            'store_inventories.inventory_id',
            'inventories.name as inventory_name',
            'stores.name as store_name',
            'specificinventory.product_name',
            'specificinventory.product_code',
            'specificinventory.Onhand',
            'specificinventory.count_1',
            'specificinventory.count_2',
            'store_inventories.status',
            'specificinventory.created_at'
        )
        ->when($selectedInventoryId, fn($query) => $query->where('store_inventories.inventory_id', $selectedInventoryId))
        ->when($selectedStoreId, fn($query) => $query->where('store_inventories.store_id', $selectedStoreId))
        ->when($selectedDate, fn($query) => $query->whereDate('specificinventory.created_at', $selectedDate))
        ->when($selectedStatus, fn($query) => $query->where('store_inventories.status', $selectedStatus))
        ->get();

    return Excel::download(new SpecificInventoryItemsExport($items), 'specific_inventory_items.xlsx');
}

}
 
