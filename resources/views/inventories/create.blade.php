<!-- resources/views/inventories/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer un Inventaire</h1>

    <form action="{{ route('inventories.store') }}" method="POST">
        @csrf
        <!-- Champ : Nom de l'inventaire -->
        <div class="form-group">
            <label for="name">Nom de l'Inventaire</label><br>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <!-- Champ : Type d'inventaire -->
        <div class="form-group">
            <label for="type">Type d'Inventaire</label><br>
            <select id="type" name="type" class="form-control" required>
                <option value="libre">libre</option>
                <option value="specific">Specific</option>
                <option value="all">all</option>
            </select>
        </div>
        <br>    
        <!-- Boutons -->
        <button type="submit" class="btn btn-primary">Créer</button>
        <a href="{{ route('inventories.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection