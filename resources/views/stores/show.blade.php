@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Détails du Magasin</h1>

        <div class="card">
            <div class="card-header">
                <h4>{{ $store->name }}</h4>
            </div>
            <div class="card-body">
                <p><strong>Emplacement :</strong> {{ $store->location ?? 'Non spécifié' }}</p>
                <p><strong>Abréviation (Abr_Store) :</strong> {{ $store->Abr_Store ?? 'Non spécifiée' }}</p>
                <p><strong>Créé le :</strong> {{ $store->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Mis à jour le :</strong> {{ $store->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <a href="{{ route('stores.edit', $store->id) }}" class="mt-3 btn btn-primary">Modifier</a>
        <a href="{{ route('stores.index') }}" class="mt-3 btn btn-secondary">Retour à la liste</a>
    </div>
@endsection
