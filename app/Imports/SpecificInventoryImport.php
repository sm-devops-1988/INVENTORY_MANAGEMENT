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
        // Vérification si 'abr_store' est présent
        if (!isset($row['abr_store'])) {
            \Log::error("La colonne 'abr_store' est absente dans le fichier importé.");
            return null;
        }
 
        // Récupérer le magasin correspondant via l'abréviation (Abr_Store)
        $store = Store::whereRaw("BINARY Abr_Store = ?", [trim($row['abr_store'])])->first();
 
        if (!$store) {
            \Log::error("Aucun magasin trouvé pour Abr_Store: " . $row['abr_store']);
            return null;
        }
 
        // Associer le magasin à l'inventaire dans `store_inventories`
        $storeInventory = StoreInventory::updateOrCreate(
            [
                'inventory_id' => $this->inventoryId,
                'store_id'     => $store->id,
            ],
            [
                'status' => 'imported',
            ]
        );
 
        // Insérer dans `specificinventory` et remplir store_inventory_id avec l'id de store_inventory
        return new SpecificInventory([
            'product_name'  => $row['product_name'],
            'product_code'  => $row['product_code'],
            'Onhand'        => $row['onhand'],
            'store_id'      => $store->id,  // Ajout du store_id correct
            'count_1'       => 0,
            'count_2'       => 0,
            'inventory_id'  => $this->inventoryId,
            'store_inventory_id' => $storeInventory->id,  // Remplir le champ store_inventory_id avec l'ID correct
        ]);
    }
}
 
