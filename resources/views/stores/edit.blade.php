@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Modifier le Magasin</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('stores.update', $store->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nom du Magasin</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $store->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Emplacement (optionnel)</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $store->location) }}">
            </div>

            <button type="submit" class="btn btn-success">Mettre à jour</button>
            <a href="{{ route('stores.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
