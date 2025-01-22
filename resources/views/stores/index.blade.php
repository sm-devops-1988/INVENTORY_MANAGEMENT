@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Liste des Magasins</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('stores.create') }}" class="btn btn-primary mb-3">Ajouter un Magasin</a>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Emplacement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stores as $store)
                    <tr>
                        <td>{{ $store->id }}</td>
                        <td>{{ $store->name }}</td>
                        <td>{{ $store->location ?? 'Non spécifié' }}</td>
                        <td>
                            <a href="{{ route('stores.show', $store->id) }}" class="btn btn-info btn-sm">Voir</a>
                            <a href="{{ route('stores.edit', $store->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                            <form action="{{ route('stores.destroy', $store->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer ce magasin ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Aucun magasin trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
