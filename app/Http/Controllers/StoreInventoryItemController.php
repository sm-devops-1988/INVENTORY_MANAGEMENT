<?php

namespace App\Http\Controllers;

use App\Models\StoreInventory;
use App\Models\StoreInventoryItem;
use Illuminate\Http\Request;

class StoreInventoryItemController extends Controller
{
    /**
     * Afficher la liste des items pour un StoreInventory donné.
     */
    public function index($storeInventoryId)
    {
        // Récupérer le StoreInventory avec ses items
        $storeInventory = StoreInventory::findOrFail($storeInventoryId);
        $storeInventoryItems = $storeInventory->storeInventoryItems; // Relation définie dans le modèle StoreInventory

        return view('storeinventoryitems.index', compact('storeInventory', 'storeInventoryItems'));
    }

    /**
     * Afficher le formulaire pour ajouter un nouvel item.
     */
    public function create($storeInventoryId)
    {
        // Récupérer le StoreInventory pour l'afficher dans le formulaire
        $storeInventory = StoreInventory::findOrFail($storeInventoryId);

        return view('storeinventoryitems.create', compact('storeInventory'));
    }

    /**
     * Enregistrer un nouvel item pour un StoreInventory.
     */
    public function store(Request $request, $storeInventoryId)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:255',
            'count_1' => 'required|integer|min:0',
            'count_2' => 'required|integer|min:0',
        ]);

        // Créer un nouvel StoreInventoryItem
        $storeInventoryItem = new StoreInventoryItem([
            'store_inventory_id' => $storeInventoryId,
            'product_name' => $request->input('product_name'),
            'product_code' => $request->input('product_code'),
            'count_1' => $request->input('count_1'),
            'count_2' => $request->input('count_2'),
        ]);

        // Sauvegarder l'item dans la base de données
        $storeInventoryItem->save();

        return redirect()->route('storeinventoryitems.index', $storeInventoryId)
                         ->with('success', 'Item ajouté avec succès.');
    }

    /**
     * Afficher les détails d'un StoreInventoryItem.
     */
    public function show($itemId)
    {
        // Récupérer l'item avec son StoreInventory associé
        $storeInventoryItem = StoreInventoryItem::findOrFail($itemId);

        return view('storeinventoryitems.show', compact('storeInventoryItem'));
    }

    /**
     * Afficher le formulaire d'édition d'un StoreInventoryItem.
     */
    public function edit($itemId)
    {
        // Récupérer l'item à éditer
        $storeInventoryItem = StoreInventoryItem::findOrFail($itemId);

        return view('storeinventoryitems.edit', compact('storeInventoryItem'));
    }

    /**
     * Mettre à jour un StoreInventoryItem.
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:255',
            'count_1' => 'required|integer|min:0',
            'count_2' => 'required|integer|min:0',
        ]);

        // Récupérer l'item à mettre à jour
        $storeInventoryItem = StoreInventoryItem::findOrFail($itemId);

        // Mettre à jour les données
        $storeInventoryItem->update([
            'product_name' => $request->input('product_name'),
            'product_code' => $request->input('product_code'),
            'count_1' => $request->input('count_1'),
            'count_2' => $request->input('count_2'),
        ]);

        return redirect()->route('storeinventoryitems.index', $storeInventoryItem->store_inventory_id)
                         ->with('success', 'Item mis à jour avec succès.');
    }

    /**
     * Supprimer un StoreInventoryItem.
     */
    public function destroy($itemId)
    {
        // Récupérer l'item à supprimer
        $storeInventoryItem = StoreInventoryItem::findOrFail($itemId);

        // Supprimer l'item
        $storeInventoryItem->delete();

        return redirect()->route('storeinventoryitems.index', $storeInventoryItem->store_inventory_id)
                         ->with('success', 'Item supprimé avec succès.');
    }
}
