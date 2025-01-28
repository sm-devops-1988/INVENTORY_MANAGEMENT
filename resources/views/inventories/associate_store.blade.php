@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Vérifier que l'inventaire n'est pas de type "specific" -->
    @if($inventory->type === 'specific')
        <div class="alert alert-danger">
            L'association manuelle de magasins n'est pas autorisée pour les inventaires de type "specific".
        </div>
    @else
        <h1>Associer des Magasins à l'Inventaire: {{ $inventory->name }}</h1>

        <form action="{{ route('inventories.associateStore', $inventory->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="store_id">Choisir un ou plusieurs magasins</label>
                <select name="store_id" id="store_id" class="form-control" required>
                    <option value="all">Tous les magasins</option> <!-- Option pour tous les magasins -->
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Associer</button>
        </form>
    @endif

    <a href="{{ route('inventories.show', $inventory->id) }}" class="mt-3 btn btn-secondary">Retour à l'Inventaire</a>
</div>
@endsection