@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Les inventaires unifiés</h1>

     <!-- Formulaire de sélection d'inventaire et de statut -->
     <form action="{{ route('StoreInventoryItem.index') }}" method="GET" class="mb-4">
    <div class="row">
        <div class="col-md-3">
            <label for="inventory_id">Sélectionner un inventaire :</label>
            <select name="inventory_id" id="inventory_id" class="form-control" onchange="this.form.submit()">
                <option value="">Tous les inventaires</option>
                @foreach ($inventories as $inventory)
                    <option value="{{ $inventory->id }}" {{ $selectedInventoryId == $inventory->id ? 'selected' : '' }}>
                        {{ $inventory->name }} ({{ $inventory->type }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="status">Sélectionner un statut :</label>
            <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" {{ $selectedStatus == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }} <!-- Afficher le statut avec une majuscule -->
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="store_id">Sélectionner un magasin :</label>
            <select name="store_id" id="store_id" class="form-control" onchange="this.form.submit()">
                <option value="">Tous les magasins</option>
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}" {{ $selectedStoreId == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="date">Sélectionner une date :</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $selectedDate }}" onchange="this.form.submit()">
        </div>
    </div>
</form>

    <!-- Bouton d'exportation -->
<form action="{{ route('StoreInventoryItem.export') }}" method="GET" class="mb-4">
    <input type="hidden" name="inventory_id" value="{{ $selectedInventoryId }}">
    <input type="hidden" name="status" value="{{ $selectedStatus }}">
    <input type="hidden" name="store_id" value="{{ $selectedStoreId }}">
    <input type="hidden" name="date" value="{{ $selectedDate }}">
    <button type="submit" class="btn btn-success">
        Exporter vers Excel
    </button>
</form>

    <!-- Tableau des éléments d'inventaire -->
    <table class="table table-bordered">
        <thead>
            <tr>
          
                <th>ID Inventaire</th>
                <th>Nom de l'inventaire</th>
                <th>Magasin</th>
                <th>Product Name</th>
                <th>Product Code</th>
                <th>Count 1</th>
                <th>Count 2</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                
                    <td>{{ $item->storeInventory->inventory->id }}</td>
                    <td>{{ $item->storeInventory->inventory->name }}</td>
                    <td>{{ $item->storeInventory->store->name }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->product_code }}</td>
                    <td>{{ $item->count_1 }}</td>
                    <td>{{ $item->count_2 }}</td>
                    <td>{{ $item->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection