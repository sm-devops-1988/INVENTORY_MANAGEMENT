@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails de l'utilisateur</h1>
    
    <div class="card">
        <div class="card-header">
            <strong>Nom :</strong> {{ $user->name }}
        </div>
        <div class="card-body">
            <p><strong>Email :</strong> {{ $user->email }}</p>
            <p><strong>Magasin :</strong> {{ $user->store->name }}</p>
        </div>
    </div>
</div>
@endsection
