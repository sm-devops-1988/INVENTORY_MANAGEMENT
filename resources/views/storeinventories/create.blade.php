{{-- resources/views/storeinventoryitems/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter un nouvel item à l'inventaire : {{ $storeInventory->id }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('storeinventoryitems.store', $storeInventory->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nom du produit</label>
            <input type="text" name="product_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Code du produit</label>
            <input type="text" name="product_code" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Quantité 1</label>
            <input type="number" name="count_1" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Quantité 2</label>
            <input type="number" name="count_2" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
    </form>
</div>
@endsection
