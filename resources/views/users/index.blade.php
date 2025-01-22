@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des Utilisateurs par Magasin</h1>

    

    <!-- Affichage des magasins et de leurs utilisateurs -->
    @foreach ($stores as $store)
        <div class="card mb-3">
            <div class="card-header">
                <strong>Magasin :</strong> {{ $store->name }}
            </div>
            <div class="card-body">
                @if ($store->users->isEmpty())
                    <p>Aucun utilisateur assigné à ce magasin.</p>
                @else
                    <ul class="list-group">
                        @foreach ($store->users as $user)
                            <li class="list-group-item">
                                {{ $user->name }} - {{ $user->email }}
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm float-right ml-2">Voir</a>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm float-right">Modifier</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
