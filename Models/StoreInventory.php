<?php

// app/Models/StoreInventory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreInventory extends Model
{
    use HasFactory;

    protected $fillable = ['inventory_id', 'store_id', 'status'];

    // Relation avec Inventory
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    // Relation avec Store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Relation avec StoreInventoryItems
    public function storeInventoryItems()
    {
        return $this->hasMany(StoreInventoryItem::class);
    }
}
