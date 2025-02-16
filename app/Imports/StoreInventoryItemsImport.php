<?php
namespace App\Imports;

use App\Models\StoreInventoryItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Session;

class StoreInventoryItemsImport implements ToModel, WithHeadingRow
{
    protected $storeInventoryId;
    protected $requiredColumns = ['product_name', 'product_code', 'onhand'];

    public function __construct($storeInventoryId)
    {
        $this->storeInventoryId = $storeInventoryId;
    }

    public function model(array $row)
    {
        // Vérifie que toutes les colonnes attendues sont bien présentes
        if (!$this->validateColumns($row)) {
            Session::flash('error', 'Le fichier importé ne correspond pas au canevas attendu. Vérifiez les colonnes.');
            return null; // Arrête l'importation
        }

        // Vérifie si `product_name` est vide
        if (!isset($row['product_name']) || empty(trim($row['product_name']))) {
            return null; // Ignore la ligne si le nom du produit est vide
        }

        return new StoreInventoryItem([
            'store_inventory_id' => $this->storeInventoryId,
            'product_name' => trim($row['product_name']),
            'product_code' => trim($row['product_code']),
            'onhand' => is_numeric($row['onhand']) ? (int)$row['onhand'] : 0, // Vérifie que `onhand` est un nombre
        ]);
    }

    /**
     * Vérifie si les colonnes du fichier importé correspondent aux colonnes requises.
     */
    private function validateColumns($row)
    {
        return empty(array_diff($this->requiredColumns, array_keys($row)));
    }
}
