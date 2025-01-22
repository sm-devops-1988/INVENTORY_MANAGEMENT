{{-- resources/views/storeinventoryitems/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Items de l'inventaire : {{ $storeInventory->id }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom du produit</th>
                <th>Code du produit</th>
                <th>Quantité 1</th>
                <th>Quantité 2</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($storeInventoryItems as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->product_code }}</td>
                    <td>{{ $item->count_1 }}</td>
                    <td>{{ $item->count_2 }}</td>
                    <td>
                        <a href="{{ route('storeinventoryitems.edit', $item->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="{{ route('storeinventoryitems.destroy', $item->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('storeinventoryitems.create', $storeInventory->id) }}" class="btn btn-primary">Ajouter un item</a>
</div>
@endsection
