<?php

// app/Models/Store.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    // Définir les champs qui peuvent être remplis
    protected $fillable = ['name', 'location','Abr_Store'];

    // Définir une relation avec StoreInventory
    public function storeInventories()
    {
        return $this->hasMany(StoreInventory::class);
    }


    public function users()
    {
    return $this->hasMany(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'Abr_Store', 'id');  // 'Abr_Store' est la clé étrangère, 'id' est la clé primaire dans la table `stores`
    }
}
