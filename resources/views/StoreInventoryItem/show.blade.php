@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails de l'item</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>Nom du produit :</strong> {{ $storeInventoryItem->product_name }}</p>
            <p><strong>Code du produit :</strong> {{ $storeInventoryItem->product_code }}</p>
            <p><strong>Compteur 1 :</strong> {{ $storeInventoryItem->count_1 }}</p>
            <p><strong>Compteur 2 :</strong> {{ $storeInventoryItem->count_2 }}</p>
            <a href="{{ route('storeinventoryitems.index', $storeInventoryItem->store_inventory_id) }}" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>
@endsection
