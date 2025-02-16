<?php
 
namespace App\Http\Controllers\API;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
 
class APISpecificInventoryController extends Controller
{
    /**
     * Get the specific inventory items for the authenticated user based on the status.
     */
   public function getUserSpecificInventory(Request $request)
{
    $userId = $request->user()->id;
 
    // Récupérer l'ID du magasin de l'utilisateur
    $storeId = DB::table('users')->where('id', $userId)->value('store_id');
 
    if (!$storeId) {
        return response()->json([
            'status' => null,
            'items' => [],
            'message' => 'Utilisateur non associé à un magasin.'
        ], 404);
    }
 
    // Trouver le dernier inventaire de type "specific"
    $latestInventory = DB::table('store_inventories as si_inv')
        ->join('inventories as inv', 'si_inv.inventory_id', '=', 'inv.id')
        ->where('si_inv.store_id', $storeId)
        ->where('inv.type', 'specific') // Filtrer sur 'specific'
        ->select('si_inv.id', 'si_inv.status')
        ->orderBy('si_inv.created_at', 'desc')
        ->first();
 
    if (!$latestInventory) {
        return response()->json([
            'status' => null,
            'items' => [],
            'message' => 'Aucun inventaire spécifique trouvé pour ce magasin.'
        ], 404);
    }
 
    // Récupérer les articles liés à cet inventaire spécifique
    $items = DB::table('specificinventory as si')
        ->join('store_inventories as si_inv', 'si.store_inventory_id', '=', 'si_inv.id')
        ->join('inventories as inv', 'si_inv.inventory_id', '=', 'inv.id')
        ->join('stores as s', 'si_inv.store_id', '=', 's.id')
        ->select(
            'si.id AS specific_inventory_id',
            'si.product_name',
            'si.product_code',
            'si.Onhand',
            'si.count_1',
            'si.count_2',
            's.name AS store_name',
            's.location AS store_location',
            'si_inv.status AS inventory_status',
            'si_inv.id AS store_inventory_id',
            'inv.name AS inventory_name',
            'inv.type AS inventory_type'
        )
        ->where('si_inv.id', $latestInventory->id)
        ->get();
 
    return response()->json([
        'status' => $latestInventory->status,
        'items' => $items,
    ]);
}
 
    /**
     * Update the count_1 value for a specific inventory item.
     */
    public function updateCount1(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'count_1' => 'required|integer|min:0',
        ]);
 
        try {
            // Update the count_1 value in the database
            DB::table('specificinventory')
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
     * Update the count_2 value for a specific inventory item.
     */
    public function updateCount2(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'count_2' => 'required|integer|min:0',
        ]);
 
        try {
            // Update the count_2 value in the database
            DB::table('specificinventory')
                ->where('id', $id)
                ->update(['count_2' => $request->count_2]);
 
            Log::info("Count 2 updated for item ID: $id");
            return response()->json(['message' => 'Count 2 updated successfully']);
        } catch (\Exception $e) {
            Log::error("Error updating count 2 for item ID: $id - " . $e->getMessage());
            return response()->json(['error' => 'Failed to update count 2'], 500);
        }
    }
 
    /**
     * Update the inventory status for a specific store.
     */
    public function updateInventoryStatus(Request $request, $storeInventoryId)
    {
        // Validate the request
        $request->validate([
            'status' => 'required|string|in:imported,closed_count1,close',
        ]);
 
        try {
            // Log the storeInventoryId and new status for debugging
            Log::info("Updating status for store inventory ID: $storeInventoryId to {$request->status}");
 
            // Update the inventory status
            $updated = DB::table('store_inventories')
                ->where('id', $storeInventoryId)
                ->update(['status' => $request->status]);
 
            // Log the result of the update
            Log::info("Update result: " . ($updated ? 'Success' : 'Failed'));
 
            return response()->json(['message' => 'Inventory status updated successfully']);
        } catch (\Exception $e) {
            Log::error("Error updating inventory status for store inventory ID: $storeInventoryId - " . $e->getMessage());
            return response()->json(['error' => 'Failed to update inventory status'], 500);
        }
    }
}