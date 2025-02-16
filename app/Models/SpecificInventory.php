<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class SpecificInventory extends Model
{
    use HasFactory;
 
    protected $table = 'specificinventory';
 
    // Utiliser 'store_inventory_id' au lieu de 'inventory_id'
    protected $fillable = ['store_inventory_id', 'product_name', 'product_code', 'Onhand'];
 
    // Relation avec StoreInventory
    public function storeInventory()
    {
        return $this->belongsTo(StoreInventory::class, 'store_inventory_id');
    }
 
    // Relation avec Inventory via StoreInventory
    public function inventory()
    {
        return $this->hasOneThrough(
            Inventory::class,
            StoreInventory::class,
            'id', // Clé primaire de StoreInventory
            'id', // Clé primaire de Inventory
            'store_inventory_id', // Clé étrangère dans SpecificInventory
            'inventory_id' // Clé étrangère dans StoreInventory
        );
    }
 
    // Relation avec Store via StoreInventory
    public function store()
    {
        return $this->hasOneThrough(
            Store::class,
            StoreInventory::class,
            'id', // Clé primaire de StoreInventory
            'id', // Clé primaire de Store
            'store_inventory_id', // Clé étrangère dans SpecificInventory
            'store_id' // Clé étrangère dans StoreInventory
        );
    }
 
   // Définition des accessors pour les champs calculés
   public function getEcart1Attribute()
   {
       return $this->count_1 - $this->Onhand;
   }
   
   public function getEcart2Attribute()
   {
       return $this->count-2 - $this->Onhand;
   }
}
