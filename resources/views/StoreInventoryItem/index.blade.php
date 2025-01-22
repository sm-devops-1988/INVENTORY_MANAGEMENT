@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Inventaire : {{ $storeInventory->name }}</h1>
    <a href="{{ route('storeinventoryitems.create', $storeInventory->id) }}" class="btn btn-primary mb-3">Ajouter un item</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($storeInventoryItems->isEmpty())
        <p>Aucun item trouvé pour cet inventaire.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom du produit</th>
                    <th>Code du produit</th>
                    <th>Compteur 1</th>
                    <th>Compteur 2</th>
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
                            <a href="{{ route('storeinventoryitems.show', $item->id) }}" class="btn btn-info btn-sm">Voir</a>
                            <a href="{{ route('storeinventoryitems.edit', $item->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                            <form action="{{ route('storeinventoryitems.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
