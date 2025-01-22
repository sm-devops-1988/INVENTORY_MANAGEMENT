@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Importer des Produits Spécifiques pour l'Inventaire : {{ $inventory->name }}</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Importer une Liste Spécifique pour Chaque Magasin</h5>
            <p class="card-text">Téléchargez un fichier Excel contenant la liste des produits avec une colonne "store_name" pour spécifier le magasin.</p>

            <!-- Import Form -->
            <form action="{{ route('importSpecific.import', $inventory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="product_file">Fichier Excel</label>
                    <input type="file" name="product_file" class="form-control-file" id="product_file" required>
                    <small class="form-text text-muted">
                        Le fichier doit contenir les colonnes suivantes : <strong>store_name</strong>, <strong>product_name</strong>, <strong>product_code</strong>, <strong>count_1</strong>, et <strong>count_2</strong>.
                    </small>
                </div>
                <button type="submit" class="btn btn-primary">Importer</button>
            </form>
        </div>
    </div>

    <a href="{{ route('inventories.index') }}" class="btn btn-secondary mt-3">Retour à la Liste des Inventaires</a>
</div>
@endsection