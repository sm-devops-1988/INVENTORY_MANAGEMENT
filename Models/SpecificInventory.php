<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificInventory extends Model
{
    use HasFactory;

    protected $table = 'specificinventory'; // Nom de la table

    protected $fillable = [
        'product_name',
        'product_code',
        'Onhand',
        'abr_store',  // Changer ici de 'id_store' à 'abr_store'
        'count_1',
        'count_2',
        'inventory_id', // Ajouter 'inventory_id' ici
    ];
}