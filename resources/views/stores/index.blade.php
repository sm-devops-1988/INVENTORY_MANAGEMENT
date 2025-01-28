@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Liste des Magasins</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('stores.create') }}" class="mb-3 btn btn-success">Créer un nouveau magasin</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Emplacement</th>
                    <th>Abréviation (Abr_Store)</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stores as $store)
                    <tr>
                        <td>{{ $store->id }}</td>
                        <td>{{ $store->name }}</td>
                        <td>{{ $store->location ?? 'Non spécifié' }}</td>
                        <td>{{ $store->Abr_Store ?? 'Non spécifiée' }}</td>
                        <td>{{ $store->created_at ? $store->created_at->format('d/m/Y H:i') : 'Non spécifié' }}</td>
                        <td>
                            <a href="{{ route('stores.show', $store->id) }}" class="btn btn-info btn-sm">Voir</a>
                            <a href="{{ route('stores.edit', $store->id) }}" class="btn btn-primary btn-sm">Modifier</a>
                            <form action="{{ route('stores.destroy', $store->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce magasin ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
