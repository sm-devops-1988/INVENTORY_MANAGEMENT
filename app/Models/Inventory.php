<?php

// app/Models/Inventory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];  // Assurez-vous que le champ 'name' est dans le tableau 'fillable'


    // Relation avec StoreInventory
    public function storeInventories()
    {
        return $this->hasMany(StoreInventory::class);
    }
}

