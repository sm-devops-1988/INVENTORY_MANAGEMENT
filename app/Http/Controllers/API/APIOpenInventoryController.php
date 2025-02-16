<?php
 
namespace App\Http\Controllers\API;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
 
class APIOpenInventoryController extends Controller
{
    /**
     * Get the open inventory items for the authenticated user based on the status.
     */
    public function getUserOpenInventory(Request $request)
    {
        $userId = $request->user()->id;
 
        // Récupérer l'ID du magasin de l'utilisateur
        $storeId = DB::table('users')->where('id', $userId)->value('store_id');
 
        if (!$storeId) {
            return response()->json([
                'status' => null,
                'message' => 'Utilisateur non associé à un magasin.'
            ], 404);
        }
 
        // Trouver le dernier inventaire de type "libre"
        $latestInventory = DB::table('store_inventories as si_inv')
            ->join('inventories as inv', 'si_inv.inventory_id', '=', 'inv.id')
            ->where('si_inv.store_id', $storeId)
            ->where('inv.type', 'libre') // Filtrer sur 'libre'
            ->select('si_inv.id', 'si_inv.status')
            ->orderBy('si_inv.created_at', 'desc')
            ->first();
 
        if (!$latestInventory) {
            return response()->json([
                'status' => null,
                'message' => 'Aucun inventaire libre trouvé pour ce magasin.'
            ], 404);
        }
 
        return response()->json([
            'status' => $latestInventory->status,
            'store_inventory_id' => $latestInventory->id, // Retourner l'ID de l'inventaire
            'message' => 'Inventaire libre récupéré avec succès.'
        ]);
    }
 
    /**
     * Update the count_1 value for an open inventory item.
     */
    public function updateCount1(Request $request, $id)
    {
        $request->validate([
            'count_1' => 'required|integer|min:0',
        ]);
 
        try {
            DB::table('open_inventory_items')
                ->where('id', $id)
                ->update(['count_1' => $request->count_1]);
 
            Log::info("Count 1 updated for item ID: $id");
            return response()->json(['message' => 'Count 1 updated successfully']);
        } catch (\Exception $e) {
            Log::error("Error updating count 1 for item ID: $id - " . $e->getMessage());
            return response()->json(['error' => 'Failed to update count 1'], 500);
        }
    }
 
    /**
     * Update the inventory status for a specific store.
     */
    public function updateInventoryStatus(Request $request, $storeInventoryId)
    {
        $request->validate([
            'status' => 'required|string|in:created,closed',
        ]);
 
        try {
            DB::table('store_inventories')
                ->where('id', $storeInventoryId)
                ->update(['status' => $request->status]);
 
            return response()->json(['message' => 'Statut de l\'inventaire mis à jour avec succès.']);
        } catch (\Exception $e) {
            Log::error("Error updating inventory status: " . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la mise à jour du statut de l\'inventaire.'], 500);
        }
    }
 
    /**
     * Sauvegarder les produits scannés dans open_inventory_items.
     */
    public function saveScannedProducts(Request $request, $storeInventoryId)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.product_code' => 'required|string',
            'products.*.count_1' => 'required|integer|min:1',
        ]);
 
        try {
            // Insérer tous les produits scannés
            foreach ($request->products as $product) {
                DB::table('open_inventory_items')->insert([
                    'store_inventory_id' => $storeInventoryId, // Clé étrangère
                    'product_code' => $product['product_code'],
                    'count_1' => $product['count_1'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
 
            return response()->json(['message' => 'Produits scannés sauvegardés avec succès.']);
        } catch (\Exception $e) {
            Log::error("Error saving scanned products: " . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la sauvegarde des produits scannés.'], 500);
        }
    }
}