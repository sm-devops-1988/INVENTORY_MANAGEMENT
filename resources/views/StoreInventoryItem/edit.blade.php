@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier l'item : {{ $storeInventoryItem->product_name }}</h1>

    <form action="{{ route('storeinventoryitems.update', $storeInventoryItem->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nom du produit</label>
            <input type="text" name="product_name" class="form-control" value="{{ $storeInventoryItem->product_name }}" required>
        </div>
        <div class="form-group">
            <label>Code du produit</label>
            <input type="text" name="product_code" class="form-control" value="{{ $storeInventoryItem->product_code }}" required>
        </div>
        <div class="form-group">
            <label>Compteur 1</label>
            <input type="number" name="count_1" class="form-control" value="{{ $storeInventoryItem->count_1 }}" required>
        </div>
        <div class="form-group">
            <label>Compteur 2</label>
            <input type="number" name="count_2" class="form-control" value="{{ $storeInventoryItem->count_2 }}" required>
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('storeinventoryitems.index', $storeInventoryItem->store_inventory_id) }}" class="btn btn-secondary">Retour</a>
    </form>
</div>
@endsection
