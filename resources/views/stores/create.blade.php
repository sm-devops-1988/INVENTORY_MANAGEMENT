@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Créer un Nouveau Magasin</h1>

        <div class="card">
            <div class="card-header">
                <h4>Formulaire de Création du Magasin</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('stores.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nom du Magasin</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="location">Emplacement</label>
                        <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}">
                        @error('location')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="Abr_Store">Abréviation du Magasin</label>
                        <input type="text" name="Abr_Store" id="Abr_Store" class="form-control" value="{{ old('Abr_Store') }}" required>
                        @error('Abr_Store')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="mt-3 btn btn-primary">Créer</button>
                </form>
            </div>
        </div>

        <a href="{{ route('stores.index') }}" class="mt-3 btn btn-secondary">Retour à la liste</a>
    </div>
@endsection
