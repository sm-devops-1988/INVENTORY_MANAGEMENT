<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OpenInventoryItemsExport implements FromCollection, WithHeadings
{
    protected $items;

    // Injecter les données filtrées dans le constructeur
    public function __construct($items)
    {
        $this->items = $items;
    }

    // Retourner la collection de données à exporter
    public function collection()
    {
        return $this->items->map(function ($item) {
            return [
                'Inventory ID' => $item->storeInventory->inventory_id ?? 'N/A', // ID de l'inventaire
                'Inventory Name' => $item->storeInventory->inventory->name ?? 'N/A', // Nom de l'inventaire
                'Store Name' => $item->storeInventory->store->name ?? 'Magasin inconnu', // Nom du magasin
                'Product Code' => $item->product_code, // Code produit
                'Count 1' => $item->count_1, // Premier comptage
                'Created At' => $item->created_at->format('Y-m-d H:i:s'), // Date de création formatée
            ];
        });
    }

    // Définir les en-têtes du fichier Excel
    public function headings(): array
    {
        return [
            'Inventory ID',
            'Inventory Name',
            'Store Name',
            'Product Code',
            'Count 1',
            'Created At',
        ];
    }
}
