@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>{{ __('Gestion des ressources') }}</h4>
                    <ul class="list-group">
                      
                        <li class="list-group-item">
                            <a href="{{ route('inventories.index') }}">Gestion des Inventaires</a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('users.index') }}">Gestion des Utilisateurs</a>
                        </li>
                     
                        <li class="list-group-item">
                            <a href="{{ route('stores.index') }}">Gestion des magasins</a>
                        </li>
                 
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
