<?php

namespace App\Imports;

use App\Models\StoreInventoryItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StoreInventoryItemsImport implements ToModel, WithHeadingRow
{
    protected $storeInventoryId;
    protected $existingProducts;

    // Constructor
    public function __construct($storeInventoryId, $existingProducts = [])
    {
        $this->storeInventoryId = $storeInventoryId;
        $this->existingProducts = $existingProducts;
    }

    // Fonction pour convertir les lignes du fichier en modèles
    public function model(array $row)
    {
        // Si le produit existe déjà, ne pas l'ajouter
        if (in_array($row['product_code'], $this->existingProducts)) {
            return null;
        }

        return new StoreInventoryItem([
            'store_inventory_id' => $this->storeInventoryId,
            'product_name' => $row['product_name'],
            'product_code' => $row['product_code'],
            'count_1' => $row['count_1'],
            'count_2' => $row['count_2'] ?? 0,
        ]);
    }
}
