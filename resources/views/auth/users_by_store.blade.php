@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Utilisateurs par Magasin</h1>
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
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
