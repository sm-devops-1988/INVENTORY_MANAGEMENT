@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Titre de la page -->
    <h1>Détails de l'Inventaire: {{ $inventory->name }}</h1>

    <!-- Informations sur la date de création de l'inventaire -->
    <p><strong>Créé le:</strong> {{ $inventory->created_at->format('d/m/Y') }}</p>

    <!-- Affichage des messages d'erreur et de succès -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Section des Magasins Associés -->
    <h3>Magasins Associés</h3>
    @if($inventory->storeInventories->isEmpty())
        <p>Aucun magasin associé à cet inventaire.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Magasin</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventory->storeInventories as $storeInventory)
                    <tr>
                        <td>{{ $storeInventory->store ? $storeInventory->store->name : 'Magasin non trouvé' }}</td>
                        <td>{{ $storeInventory->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Afficher le formulaire d'importation de produits uniquement si tous les magasins ne sont pas encore importés -->
    @if(!$inventory->storeInventories->every(fn($storeInventory) => $storeInventory->status === 'imported'))
        <h3 class="mt-4">Importer des Produits pour tous les magasins associés</h3>
        <form action="{{ route('inventories.import', $inventory->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Sélection du fichier Excel ou CSV -->
            <div class="form-group">
                <label for="product_file">Fichier Excel ou CSV des produits</label>
                <input type="file" name="product_file" class="form-control" required>
            </div>

            <!-- Sélection du magasin associé (optionnel) -->
            <div class="form-group">
                <label for="store_inventory_id">Sélectionner un magasin associé (optionnel)</label>

                <!-- Liste déroulante pour sélectionner un magasin créé -->
                <select name="store_inventory_id" class="form-control">
                    <option value="">Tous les magasins</option>
                    @foreach ($inventory->storeInventories as $storeInventory)
                        @if($storeInventory->status == 'created')
                            <option value="{{ $storeInventory->id }}">{{ $storeInventory->store->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Bouton pour soumettre le formulaire d'importation -->
            <button type="submit" class="btn btn-primary">Importer les produits</button>
        </form>
    @else
        <p>Tous les magasins associés ont déjà été importés.</p>
    @endif

    <!-- Liens pour ajouter un magasin ou revenir à la liste des inventaires -->
    <div class="mt-3">
        <a href="{{ route('inventories.showAssociateStoreForm', $inventory->id) }}" class="btn btn-primary">Associer un Magasin</a>
        <a href="{{ route('inventories.index') }}" class="btn btn-secondary">Retour à la Liste</a>
    </div>
</div>
@endsection
