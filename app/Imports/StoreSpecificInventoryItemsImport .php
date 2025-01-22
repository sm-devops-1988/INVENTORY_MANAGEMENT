<?php

namespace App\Imports;

use App\Models\StoreInventoryItem;
use App\Models\Store;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StoreSpecificInventoryItemsImport implements ToModel, WithHeadingRow
{
    protected $inventoryId;

    public function __construct($inventoryId)
    {
        $this->inventoryId = $inventoryId;
    }

    public function model(array $row)
    {
        // Find the store by name
        $store = Store::where('name', $row['store_name'])->first();

        if (!$store) {
            return null; // Skip if the store doesn't exist
        }

        // Find the store inventory for the given store and inventory
        $storeInventory = StoreInventory::where('inventory_id', $this->inventoryId)
                                        ->where('store_id', $store->id)
                                        ->first();

        if (!$storeInventory) {
            return null; // Skip if the store inventory doesn't exist
        }

        // Check if the product already exists for this store inventory
        $existingItem = StoreInventoryItem::where('store_inventory_id', $storeInventory->id)
                                          ->where('product_code', $row['product_code'])
                                          ->exists();

        if ($existingItem) {
            return null; // Skip if the product already exists
        }

        return new StoreInventoryItem([
            'store_inventory_id' => $storeInventory->id,
            'product_name' => $row['product_name'],
            'product_code' => $row['product_code'],
            'count_1' => $row['count_1'],
            'count_2' => $row['count_2'] ?? 0,
        ]);
    }
}