<?php
namespace App\Imports;

use App\Models\SpecificInventory;
use App\Models\StoreInventory;
use App\Models\Store;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SpecificInventoryImport implements ToModel, WithHeadingRow
{
    protected $inventoryId;

    public function __construct($inventoryId)
    {
        $this->inventoryId = $inventoryId;
    }

    public function model(array $row)
    {
        // Récupérer le magasin correspondant à partir de l'abréviation du magasin (Abr_Store)
        $store = Store::where('Abr_Store', $row['abr_store'])->first();

        if ($store) {
            // Associer le magasin à l'inventaire dans la table store_inventories
            StoreInventory::updateOrCreate(
                [
                    'inventory_id' => $this->inventoryId,
                    'store_id' => $store->id,
                ],
                [
                    'status' => 'imported', // Vous pouvez ajuster le statut selon vos besoins
                ]
            );
        }

        // Créer et retourner le modèle SpecificInventory
        return new SpecificInventory([
            'product_name' => $row['product_name'],
            'product_code' => $row['product_code'],
            'Onhand' => $row['onhand'],
            'abr_store' => $row['abr_store'], // Utilisation de abr_store
            'count_1' => 0,
            'count_2' => 0,
            'inventory_id' => $this->inventoryId, // Associer l'inventaire au produit spécifique
        ]);
    }
}