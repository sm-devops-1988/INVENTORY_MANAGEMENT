@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des Utilisateurs par Magasin</h1>

    <!-- Bouton pour ajouter un nouvel utilisateur -->
    <div class="mb-3">
        <a href="{{ route('users.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Ajouter un Utilisateur
        </a>
    </div>

    <!-- Tableau des utilisateurs -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Magasin</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->store->name }}</td> <!-- Accéder au magasin via la relation -->
                        <td>{{ $user->email }}</td>
                        <td>
                            <!-- Bouton "Voir" -->
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm" title="Voir">
                                <i class="fas fa-eye"></i> Voir
                            </a>

                            <!-- Bouton "Modifier" -->
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                <i class="fas fa-edit"></i> Modifier
                            </a>

                            <!-- Bouton "Supprimer" -->
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>
@endsection