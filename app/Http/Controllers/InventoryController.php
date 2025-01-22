<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StoreInventory;
use App\Models\StoreInventoryItem;
use Illuminate\Http\Request;
use App\Models\Store;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StoreInventoryItemsImport;
use App\Imports\StoreSpecificInventoryItemsImport;
use App\Http\Controllers\InventoryController;

class InventoryController extends Controller
{
    // Display a list of all inventories
    public function index()
    {
        $inventories = Inventory::all();
        return view('inventories.index', compact('inventories'));
    }

    // Display a specific inventory with its associated stores and products
    public function show($inventoryId)
    {
        // Retrieve the inventory with associated stores and products
        $inventory = Inventory::with('storeInventories.storeInventoryItems')->findOrFail($inventoryId);

        // Retrieve the associated store inventories
        $storeInventories = $inventory->storeInventories;

        return view('inventories.show', compact('inventory', 'storeInventories'));
    }

    // Display the form to create a new inventory
    public function create()
    {
        return view('inventories.create');
    }

    // Save a new inventory to the database
    public function store(Request $request)
{
    // Validation des données
    $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:libre,specific,all', // Ajoutez cette ligne
    ]);

    // Création de l'inventaire
    $inventory = Inventory::create([
        'name' => $request->name,
        'type' => $request->type, // Ajoutez cette ligne
    ]);

    return redirect()->route('inventories.index')->with('success', 'Inventaire créé avec succès.');
}
    // Display the form to edit an inventory
    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('inventories.edit', compact('inventory'));
    }

    // Update an existing inventory
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $inventory = Inventory::findOrFail($id);
        $inventory->update([
            'name' => $request->name,
        ]);

        return redirect()->route('inventories.index')->with('success', 'Inventaire mis à jour avec succès.');
    }

    // Delete an inventory
    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return redirect()->route('inventories.index')->with('success', 'Inventaire supprimé avec succès.');
    }

    // Associate a store with an inventory
    public function associateStore($inventoryId, Request $request)
    {
        $request->validate([
            'store_id' => 'required',
        ]);

        if ($request->store_id === 'all') {
            // Associate all stores not yet associated
            $stores = Store::whereNotIn('id', function ($query) use ($inventoryId) {
                $query->select('store_id')
                      ->from('store_inventories')
                      ->where('inventory_id', $inventoryId);
            })->get();

            foreach ($stores as $store) {
                StoreInventory::create([
                    'inventory_id' => $inventoryId,
                    'store_id' => $store->id,
                    'status' => 'created',
                ]);
            }

            return redirect()->route('inventories.show', $inventoryId)
                             ->with('success', 'Tous les magasins ont été associés à l\'inventaire.');
        }

        // Associate a single store
        $request->validate([
            'store_id' => 'exists:stores,id',
        ]);

        $existingAssociation = StoreInventory::where('inventory_id', $inventoryId)
                                             ->where('store_id', $request->store_id)
                                             ->exists();

        if ($existingAssociation) {
            return redirect()->route('inventories.show', $inventoryId)
                             ->with('error', 'Le magasin est déjà associé à cet inventaire.');
        }

        StoreInventory::create([
            'inventory_id' => $inventoryId,
            'store_id' => $request->store_id,
            'status' => 'created',
        ]);

        return redirect()->route('inventories.show', $inventoryId)
                         ->with('success', 'Magasin associé à l\'inventaire.');
    }

    // Add a product to a store inventory
    public function addProductToStoreInventory($inventoryId, $storeInventoryId, Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|unique:store_inventory_items,product_code',
            'count_1' => 'required|numeric|min:0',
            'count_2' => 'nullable|numeric|min:0',
        ]);

        StoreInventoryItem::create([
            'store_inventory_id' => $storeInventoryId,
            'product_name' => $request->product_name,
            'product_code' => $request->product_code,
            'count_1' => $request->count_1,
            'count_2' => $request->count_2 ?? 0,
        ]);

        return redirect()->route('inventories.show', $inventoryId)->with('success', 'Produit ajouté à l\'inventaire.');
    }

    // Import products for all stores
    public function import(Request $request, $inventoryId)
    {
        // Validate the file
        $request->validate([
            'product_file' => 'required|mimes:xlsx,csv',
            'store_inventory_id' => 'nullable|exists:store_inventories,id', // Optional, if a specific store is selected
        ]);

        // Retrieve the inventory
        $inventory = Inventory::findOrFail($inventoryId);

        // Process the Excel/CSV file
        $file = $request->file('product_file');

        // If a specific store is selected, import for that store
        if ($request->store_inventory_id) {
            $storeInventoryId = $request->store_inventory_id;

            // Verify before importing
            $importedItems = Excel::toArray(new StoreInventoryItemsImport($storeInventoryId), $file)[0];
            foreach ($importedItems as $item) {
                // Check if the product already exists for this store
                $existingItem = StoreInventoryItem::where('store_inventory_id', $storeInventoryId)
                                                  ->where('product_code', $item['product_code'])
                                                  ->exists();

                // If the product already exists, skip it
                if ($existingItem) {
                    continue;
                }

                // If the product doesn't exist, add it
                StoreInventoryItem::create([
                    'store_inventory_id' => $storeInventoryId,
                    'product_name' => $item['product_name'],
                    'product_code' => $item['product_code'],
                    'count_1' => $item['count_1'],
                    'count_2' => $item['count_2'] ?? 0,
                ]);
            }

            // Update the store inventory status to "imported"
            $storeInventory = StoreInventory::findOrFail($storeInventoryId);
            $storeInventory->status = 'imported';
            $storeInventory->save();
        } else {
            // If no specific store is selected, import for all stores associated with the inventory
            foreach ($inventory->storeInventories as $storeInventory) {
                $importedItems = Excel::toArray(new StoreInventoryItemsImport($storeInventory->id), $file)[0];
                foreach ($importedItems as $item) {
                    // Check if the product already exists for this store
                    $existingItem = StoreInventoryItem::where('store_inventory_id', $storeInventory->id)
                                                      ->where('product_code', $item['product_code'])
                                                      ->exists();

                    if ($existingItem) {
                        continue; // Skip if the product already exists
                    }

                    // If the product doesn't exist, add it
                    StoreInventoryItem::create([
                        'store_inventory_id' => $storeInventory->id,
                        'product_name' => $item['product_name'],
                        'product_code' => $item['product_code'],
                        'count_1' => $item['count_1'],
                        'count_2' => $item['count_2'] ?? 0,
                    ]);
                }

                // Update the store inventory status to "imported"
                $storeInventory->status = 'imported';
                $storeInventory->save();
            }
        }

        // Return a success message
        return redirect()->back()->with('success', 'Les produits ont été importés avec succès et le statut a été mis à jour.');
    }

    // Import store-specific products
    public function importSpecific(Request $request, $inventoryId)
    {
        // Validate the file
        $request->validate([
            'product_file' => 'required|mimes:xlsx,csv',
        ]);

        // Process the file
        Excel::import(new StoreSpecificInventoryItemsImport($inventoryId), $request->file('product_file'));

        // Update the status of all store inventories
        StoreInventory::where('inventory_id', $inventoryId)->update(['status' => 'imported']);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Les produits spécifiques aux magasins ont été importés avec succès.');
    }

    // Show the import form
    public function showImportForm($inventoryId)
    {
        // Find the inventory
        $inventory = Inventory::findOrFail($inventoryId);

        // Pass the inventory to the view
        return view('inventories.import', compact('inventory'));
    }

    // Show the form to associate a store with an inventory
    public function showAssociateStoreForm($inventoryId)
    {
        $inventory = Inventory::findOrFail($inventoryId);
        $stores = Store::all(); // Retrieve all stores
        return view('inventories.associate_store', compact('inventory', 'stores'));
    }
}