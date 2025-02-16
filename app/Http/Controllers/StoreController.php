<?php 

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\SpecificInventory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SpecificInventoryImport;


class StoreController extends Controller
{
    // Affiche la liste des magasins
    public function index()
    {
        $stores = Store::all();
        return view('stores.index', compact('stores'));
    }

    // Affiche le formulaire de création d'un magasin
    public function create()
    {
        return view('stores.create');
    }

    // Enregistre un nouveau magasin
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'Abr_Store' => 'required|string|max:255', // Validation pour Abr_Store
        ]);

        Store::create($request->all());
        return redirect()->route('stores.index')->with('success', 'Magasin créé avec succès.');
    }

    // Affiche un magasin spécifique
    public function show(Store $store)
    {
        return view('stores.show', compact('store'));
    }

    // Affiche le formulaire d'édition d'un magasin
    public function edit(Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    // Met à jour les informations d'un magasin
    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'Abr_Store' => 'required|string|max:255', // Validation pour Abr_Store
        ]);

        $store->update($request->all());
        return redirect()->route('stores.index')->with('success', 'Magasin mis à jour avec succès.');
    }

    // Supprime un magasin
    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('stores.index')->with('success', 'Magasin supprimé avec succès.');
    }

    // Importer l'inventaire spécifique avec abr_store
    public function importSpecificInventory(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Récupérer le fichier
        $file = $request->file('file');

        // Importer le fichier Excel
        Excel::import(new SpecificInventoryImport($request->inventory_id), $file);

        return redirect()->route('specificinventory.index')->with('success', 'Inventaire spécifique importé avec succès.');
    }
}
