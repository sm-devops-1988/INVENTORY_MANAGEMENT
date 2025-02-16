<?php
 
namespace App\Exports;
 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
 
class SpecificInventoryItemsExport implements FromCollection, WithHeadings
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
            'ID Inventaire' => $item->inventory_id,
            'Nom de l\'inventaire' => $item->inventory_name,
            'Magasin' => $item->store_name,
            'Product Name' => $item->product_name,
            'Product Code' => $item->product_code,
            'Onhand' => $item->Onhand,
            'Count 1' => $item->count_1,
            'Count 2' => $item->count_2,
            'Ecart 1' => $item->ecart1, // Champ calculé
            'Ecart 2' => $item->ecart2, // Champ calculé
            'Created At' => $item->created_at->format('d/m/Y'), // Formatage de la date
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
            'Onhand',
            'Count 1',
            'Count 2',
            'Ecart 1',
            'Ecart 2',
            'Created At',
        ];
    }
}