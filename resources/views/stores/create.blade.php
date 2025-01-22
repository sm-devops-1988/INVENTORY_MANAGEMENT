@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Ajouter un Magasin</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('stores.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nom du Magasin</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Emplacement (optionnel)</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}">
            </div>

            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('stores.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
