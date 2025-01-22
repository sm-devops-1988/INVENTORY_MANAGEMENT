@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter un nouvel item pour l'inventaire : {{ $storeInventory->name }}</h1>

    <form action="{{ route('storeinventoryitems.store', $storeInventory->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nom du produit</label>
            <input type="text" name="product_name" class="form-control" value="{{ old('product_name') }}" required>
        </div>
        <div class="form-group">
            <label>Code du produit</label>
            <input type="text" name="product_code" class="form-control" value="{{ old('product_code') }}" required>
        </div>
        <div class="form-group">
            <label>Compteur 1</label>
            <input type="number" name="count_1" class="form-control" value="{{ old('count_1') }}" required>
        </div>
        <div class="form-group">
            <label>Compteur 2</label>
            <input type="number" name="count_2" class="form-control" value="{{ old('count_2') }}" required>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="{{ route('storeinventoryitems.index', $storeInventory->id) }}" class="btn btn-secondary">Retour</a>
    </form>
</div>
@endsection
