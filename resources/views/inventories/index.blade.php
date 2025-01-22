@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des Inventaires</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('inventories.create') }}" class="btn btn-primary mb-3">Créer un Nouvel Inventaire</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Créé le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventories as $inventory)
                <tr>
                    <td>{{ $inventory->name }}</td>
                    <td>{{ $inventory->created_at->format('d/m/Y') }}</td>
                 
                    <td>
               
    <!-- View Button -->
    <a href="{{ route('inventories.show', $inventory->id) }}" class="btn btn-info">Voir</a>

    <!-- Edit Button -->
    <a href="{{ route('inventories.edit', $inventory->id) }}" class="btn btn-warning">Éditer</a>

    <!-- Delete Button -->
    <form action="{{ route('inventories.destroy', $inventory->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet inventaire ?')">Supprimer</button>
    </form>


                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection