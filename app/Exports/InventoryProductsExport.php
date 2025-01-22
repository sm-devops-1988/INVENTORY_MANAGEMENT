<?php

namespace App\Exports;

use App\Models\InventoryProduct;
use Maatwebsite\Excel\Concerns\FromCollection;

class InventoryProductsExport implements FromCollection
{
    public function collection()
    {
        return InventoryProduct::all(); // Récupère toutes les données du modèle InventoryProduct
    }
}
