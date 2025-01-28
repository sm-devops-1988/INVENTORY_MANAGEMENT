<?php

// app/Http/Controllers/StoreInventoryController.php

namespace App\Http\Controllers;

use App\Models\StoreInventory;
use Illuminate\Http\Request;

class StoreInventoryController extends Controller
{

    public function index($storeInventoryId = null)
    {
        if ($storeInventoryId) {
            // Charger l'inventaire spécifique
            $storeInventory = StoreInventory::findOrFail($storeInventoryId);
    
            // Passer à une vue différente pour l'inventaire spécifique
            return view('storeinventories.show', compact('storeInventory'));
        } else {
            // Charger tous les inventaires
            $storeInventories = StoreInventory::all();
    
            // Passer la liste complète à la vue
            return view('storeinventories.index', compact('storeInventories'));
        }
    }
    
    public function show($storeInventoryId)
    {
        // Récupérer l'inventaire par son ID
        $storeInventory = StoreInventory::findOrFail($storeInventoryId);
    
        // Passer l'inventaire à la vue
        return view('storeinventories.show', compact('storeInventory'));
    }
    


    // Afficher le formulaire de création d'un store inventory
    public function create($storeInventoryId)
    {
        $storeInventory = StoreInventory::findOrFail($storeInventoryId);
    
        return view('storeinventoryitems.create', compact('storeInventory'));
    }
    
    // Enregistrer un nouveau store inventory
    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
            'inventory_id' => 'required',
            'status' => 'required',
        ]);

        StoreInventory::create($request->all());
        return redirect()->route('storeinventories.index');
    }

 
}
