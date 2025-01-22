{{-- resources/views/storeinventoryitems/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier l'item : {{ $storeInventoryItem->product_name }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
            <label>Quantité 1</label>
            <input type="number" name="count_1" class="form-control" value="{{ $storeInventoryItem->count_1 }}" required>
        </div>
        <div class="form-group">
            <label>Quantité 2</label>
            <input type="number" name="count_2" class="form-control" value="{{ $storeInventoryItem->count_2 }}" required>
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
    </form>
</div>
@endsection
