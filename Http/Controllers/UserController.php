<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;

class UserController extends Controller
{
    // Affiche tous les magasins avec leurs utilisateurs
    public function index(Request $request)
    {
        // Paginer tous les utilisateurs (10 par page)
        $users = User::with('store')->paginate(10);
    
        return view('users.index', compact('users'));
    }
    // Affiche un utilisateur spécifique
    public function show($id)
    {
        $user = User::findOrFail($id); // Trouver l'utilisateur par son ID
        return view('users.show', compact('user'));
    }

    // Affiche le formulaire de création d'un nouvel utilisateur
    public function create()
    {
        $stores = Store::all(); // Récupère tous les magasins
        return view('users.create', compact('stores'));
    }

    // Enregistre un nouvel utilisateur
    public function store(Request $request)
    {
        try {
            // Valider la requête
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'store_id' => 'required|exists:stores,id', // Valider le store_id
            ]);
    
            // Créer l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'store_id' => $request->store_id,
            ]);
    
            return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création de l\'utilisateur : ' . $e->getMessage());
        }
    }

    // Affiche le formulaire pour éditer un utilisateur
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $stores = Store::all(); // Récupère tous les magasins
        return view('users.edit', compact('user', 'stores'));
    }

    // Met à jour un utilisateur existant
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Valider la requête
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'store_id' => 'required|exists:stores,id', // Valider le store_id
        ]);

        // Mettre à jour l'utilisateur
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'store_id' => $request->store_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès');
    }

    // Supprime un utilisateur
    public function destroy($id)
    {
        // Trouver l'utilisateur par son ID
        $user = User::findOrFail($id);

        // Supprimer l'utilisateur
        $user->delete();

        // Rediriger vers la liste des utilisateurs avec un message de succès
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    // Affiche les utilisateurs par magasin
    public function byStore(Request $request)
    {
        $storeId = $request->input('store_id');
        $stores = Store::with('users')->get(); // Vous récupérez les magasins et leurs utilisateurs
    
        if ($storeId) {
            $stores = $stores->where('id', $storeId); // Filtrer selon le magasin sélectionné
        }

        return view('users.index', compact('stores'));
    }
}