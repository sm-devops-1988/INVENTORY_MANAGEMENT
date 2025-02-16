<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenInventory extends Model
{
    use HasFactory;

    protected $table = 'open_inventory_items';  // Assure-toi que le nom de la table est correct

    protected $fillable = [
        'store_inventory_id',
        'product_code',
        'count_1',
    ];

    // Relation avec StoreInventory
    public function storeInventory()
    {
        return $this->belongsTo(StoreInventory::class, 'store_inventory_id');
    }

    // Relation avec Store
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_inventory_id', 'id');
    }


}
