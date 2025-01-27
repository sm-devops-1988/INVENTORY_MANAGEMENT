<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StoreInventoryItemsExport implements FromCollection, WithHeadings
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
                'ID Inventaire' => $item->storeInventory->inventory->id,
                'Nom de l\'inventaire' => $item->storeInventory->inventory->name,
                'Magasin' => $item->storeInventory->store->name,
                'Product Name' => $item->product_name,
                'Product Code' => $item->product_code,
                'Count 1' => $item->count_1,
                'Count 2' => $item->count_2,
                'Created At' => $item->created_at,
            ];
        });
    }

    // Définir les en-têtes du fichier Excel
    public function headings(): array
    {
        return [
            'ID Inventaire',
            'Nom de l\'inventaire',
            'Magasin',
            'Product Name',
            'Product Code',
            'Count 1',
            'Count 2',
            'Created At',
        ];
    }
}