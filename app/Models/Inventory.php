<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    // Champs remplissables (mass assignable)
    protected $fillable = [
        'name',
        'type', // Ajoutez cette ligne
    ];

    // Relation avec StoreInventory
    public function storeInventories()
    {
        return $this->hasMany(StoreInventory::class);
    }
}
