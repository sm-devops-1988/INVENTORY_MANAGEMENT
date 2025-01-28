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

            <div class="form-group">
                <label for="name">Nom du Magasin</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $store->name) }}" required>
            </div>

            <div class="form-group">
                <label for="location">Emplacement</label>
                <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $store->location) }}">
            </div>

            <div class="form-group">
                <label for="Abr_Store">Abréviation (Abr_Store)</label>
                <input type="text" name="Abr_Store" id="Abr_Store" class="form-control" value="{{ old('Abr_Store', $store->Abr_Store) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('stores.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
