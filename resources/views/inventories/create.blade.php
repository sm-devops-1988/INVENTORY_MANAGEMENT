<!-- resources/views/inventories/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer un Inventaire</h1>

    <form action="{{ route('inventories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nom de l'Inventaire</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
        <a href="{{ route('inventories.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
