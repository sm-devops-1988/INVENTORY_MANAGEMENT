<?php

// app/Models/Store.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    // Définir les champs qui peuvent être remplis
    protected $fillable = ['name', 'location'];

    // Définir une relation avec StoreInventory
    public function storeInventories()
    {
        return $this->hasMany(StoreInventory::class);
    }


    public function users()
    {
    return $this->hasMany(User::class);
    }

}
