<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreInventoryItem extends Model
{
    protected $fillable = [
        'store_inventory_id',
        'product_name',
        'product_code',
        'onhand', // Nouvelle colonne Onhand
    ];

    // Relation avec StoreInventory
    public function storeInventory()
    {
        return $this->belongsTo(StoreInventory::class);
    }
}