<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StoreInventory;
use App\Models\StoreInventoryItem;
use App\Models\SpecificInventory;
use Illuminate\Http\Request;
use App\Models\Store;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StoreInventoryItemsImport;
use App\Imports\SpecificInventoryImport;

class InventoryController extends Controller
{
    // Afficher la liste de tous les inventaires
    public function index()
    {
        $inventories = Inventory::all();
        return view('inventories.index', compact('inventories'));
    }

    // Afficher les détails d'un inventaire spécifique
    public function show($inventoryId)
    {
        // Récupérer l'inventaire avec les magasins et produits associés
        $inventory = Inventory::with('storeInventories.storeInventoryItems')->findOrFail($inventoryId);

        // Récupérer les magasins associés
        $storeInventories = $inventory->storeInventories;

        return view('inventories.show', compact('inventory', 'storeInventories'));
    }

    // Afficher le formulaire de création d'un inventaire
    public function create()
    {
        return view('inventories.create');
    }

    // Enregistrer un nouvel inventaire dans la base de données
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:libre,specific,all',
        ]);

        // Création de l'inventaire
        $inventory = Inventory::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()->route('inventories.index')->with('success', 'Inventaire créé avec succès.');
    }

    // Afficher le formulaire de modification d'un inventaire
    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('inventories.edit', compact('inventory'));
    }

    // Mettre à jour un inventaire existant
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

    // Supprimer un inventaire
    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return redirect()->route('inventories.index')->with('success', 'Inventaire supprimé avec succès.');
    }

    // Associer un magasin à un inventaire
    public function associateStore($inventoryId, Request $request)
    {
        // Récupérer l'inventaire
        $inventory = Inventory::findOrFail($inventoryId);

        // Vérifier que l'inventaire n'est pas de type "specific"
        if ($inventory->type === 'specific') {
            return redirect()->route('inventories.show', $inventoryId)
                             ->with('error', 'L\'association manuelle de magasins n\'est pas autorisée pour les inventaires de type "specific".');
        }

        $request->validate([
            'store_id' => 'required',
        ]);

        if ($request->store_id === 'all') {
            // Associer tous les magasins non encore associés
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

        // Associer un seul magasin
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

    // Ajouter un produit à un inventaire de magasin
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

    // Importer des produits pour tous les magasins
    public function import(Request $request, $inventoryId)
    {
        // Valider le fichier
        $request->validate([
            'product_file' => 'required|mimes:xlsx,csv',
            'store_inventory_id' => 'nullable|exists:store_inventories,id', // Optionnel, si un magasin spécifique est sélectionné
        ]);

        // Récupérer l'inventaire
        $inventory = Inventory::findOrFail($inventoryId);

        // Traiter le fichier Excel/CSV
        $file = $request->file('product_file');

        // Si un magasin spécifique est sélectionné, importer pour ce magasin
        if ($request->store_inventory_id) {
            $storeInventoryId = $request->store_inventory_id;

            // Vérifier avant d'importer
            $importedItems = Excel::toArray(new StoreInventoryItemsImport($storeInventoryId), $file)[0];
            foreach ($importedItems as $item) {
                // Vérifier si le produit existe déjà pour ce magasin
                $existingItem = StoreInventoryItem::where('store_inventory_id', $storeInventoryId)
                                                  ->where('product_code', $item['product_code'])
                                                  ->exists();

                // Si le produit existe déjà, l'ignorer
                if ($existingItem) {
                    continue;
                }

                // Si le produit n'existe pas, l'ajouter
                StoreInventoryItem::create([
                    'store_inventory_id' => $storeInventoryId,
                    'product_name' => $item['product_name'],
                    'product_code' => $item['product_code'],
                    'count_1' => $item['count_1'],
                    'count_2' => $item['count_2'] ?? 0,
                ]);
            }

            // Mettre à jour le statut de l'inventaire du magasin à "imported"
            $storeInventory = StoreInventory::findOrFail($storeInventoryId);
            $storeInventory->status = 'imported';
            $storeInventory->save();
        } else {
            // Si aucun magasin spécifique n'est sélectionné, importer pour tous les magasins associés à l'inventaire
            foreach ($inventory->storeInventories as $storeInventory) {
                $importedItems = Excel::toArray(new StoreInventoryItemsImport($storeInventory->id), $file)[0];
                foreach ($importedItems as $item) {
                    // Vérifier si le produit existe déjà pour ce magasin
                    $existingItem = StoreInventoryItem::where('store_inventory_id', $storeInventory->id)
                                                      ->where('product_code', $item['product_code'])
                                                      ->exists();

                    if ($existingItem) {
                        continue; // Ignorer si le produit existe déjà
                    }

                    // Si le produit n'existe pas, l'ajouter
                    StoreInventoryItem::create([
                        'store_inventory_id' => $storeInventory->id,
                        'product_name' => $item['product_name'],
                        'product_code' => $item['product_code'],
                        'count_1' => $item['count_1'],
                        'count_2' => $item['count_2'] ?? 0,
                    ]);
                }

                // Mettre à jour le statut de l'inventaire du magasin à "imported"
                $storeInventory->status = 'imported';
                $storeInventory->save();
            }
        }

        // Retourner un message de succès
        return redirect()->back()->with('success', 'Les produits ont été importés avec succès et le statut a été mis à jour.');
    }

    // Importer des produits spécifiques pour un inventaire de type "specific"
public function importSpecific(Request $request, $inventoryId)
{
    // Valider le fichier
    $request->validate([
        'file' => 'required|mimes:xlsx,csv',
    ]);

    // Récupérer l'inventaire
    $inventory = Inventory::findOrFail($inventoryId);

    // Vérifier que l'inventaire est de type "specific"
    if ($inventory->type !== 'specific') {
        return redirect()->back()->with('error', 'L\'importation est uniquement autorisée pour les inventaires de type "specific".');
    }

    // Importer le fichier Excel
    Excel::import(new SpecificInventoryImport($inventory->id), $request->file('file'));

    // Récupérer les magasins associés à partir du fichier Excel
    $stores = $this->getStoresFromExcel($request->file('file'));

    // Associer les magasins à l'inventaire dans la table store_inventories
    foreach ($stores as $store) {
        StoreInventory::updateOrCreate(
            [
                'inventory_id' => $inventory->id,
                'store_id' => $store->id,
            ],
            [
                'status' => 'imported', // Vous pouvez ajuster le statut selon vos besoins
            ]
        );
    }

    // Retourner un message de succès
    return redirect()->back()->with('success', 'Les produits spécifiques et les associations de magasins ont été importés avec succès.');
}

// Méthode pour récupérer les magasins à partir du fichier Excel
private function getStoresFromExcel($file)
{
    $stores = [];
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($file->getPathname());
    $worksheet = $spreadsheet->getActiveSheet();

    foreach ($worksheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $rowData = [];
        foreach ($cellIterator as $cell) {
            $rowData[] = $cell->getValue();
        }

        // Supposons que la colonne 'Abr_Store' est la quatrième colonne (index 3)
        $abbrStore = $rowData[3];

        // Récupérer le magasin correspondant
        $store = Store::where('Abr_Store', $abbrStore)->first();
        if ($store) {
            $stores[] = $store;
        }
    }

    return $stores;
}

    // Afficher le formulaire d'importation
    public function showImportForm($inventoryId)
    {
        // Trouver l'inventaire
        $inventory = Inventory::findOrFail($inventoryId);

        // Passer l'inventaire à la vue
        return view('inventories.import', compact('inventory'));
    }

    // Afficher le formulaire pour associer un magasin à un inventaire
    public function showAssociateStoreForm($inventoryId)
{
    $inventory = Inventory::findOrFail($inventoryId);

    // Vérifier que l'inventaire n'est pas de type "specific"
    if ($inventory->type === 'specific') {
        return redirect()->route('inventories.show', $inventoryId)
                         ->with('error', 'L\'association manuelle de magasins n\'est pas autorisée pour les inventaires de type "specific".');
    }

    $stores = Store::all(); // Récupérer tous les magasins
    return view('inventories.associate_store', compact('inventory', 'stores'));
}
}