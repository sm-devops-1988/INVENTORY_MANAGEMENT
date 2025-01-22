{{-- resources/views/storeinventories/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails de l'inventaire : {{ $storeInventory->id }}</h1>

    <h2>Items associés</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom du produit</th>
                <th>Code du produit</th>
                <th>Quantité 1</th>
                <th>Quantité 2</th>
            </tr>
        </thead>
        <tbody>
            @foreach($storeInventory->storeInventoryItems as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->product_code }}</td>
                    <td>{{ $item->count_1 }}</td>
                    <td>{{ $item->count_2 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('storeinventoryitems.index', $storeInventory->id) }}" class="btn btn-secondary">Retour à la liste</a>
</div>
@endsection
