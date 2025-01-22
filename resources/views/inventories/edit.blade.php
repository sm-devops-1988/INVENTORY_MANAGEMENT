@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Éditer l'Inventaire: {{ $inventory->name }}</h1>

    <form action="{{ route('inventories.update', $inventory->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nom de l'Inventaire</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $inventory->name) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à Jour</button>
        <a href="{{ route('inventories.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
