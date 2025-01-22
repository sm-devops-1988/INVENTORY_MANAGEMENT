<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreInventoryItem extends Model
{
    use HasFactory;

    protected $fillable = ['store_inventory_id', 'product_name', 'product_code', 'count_1', 'count_2'];

    public function storeInventory()
    {
        return $this->belongsTo(StoreInventory::class);
    }
}
