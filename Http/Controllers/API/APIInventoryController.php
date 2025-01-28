<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class APIInventoryController extends Controller
{
    /**
     * Get the inventory items for the authenticated user based on the status.
     */
    public function getUserInventory(Request $request)
    {
        $userId = $request->user()->id; // Get the authenticated user
    
        // Fetch the inventory status for the user's store
        $inventoryStatus = DB::table('store_inventories as si_inv')
            ->join('stores as s', 'si_inv.store_id', '=', 's.id')
            ->join('users as u', 'u.store_id', '=', 's.id')
            ->where('u.id', $userId)
            ->value('si_inv.status');
    
        // If the inventory status is 'imported' or 'closed_count1', return the inventory list
        if ($inventoryStatus === 'imported' || $inventoryStatus === 'closed_count1') {
            $items = DB::table('store_inventory_items as si')
                ->join('store_inventories as si_inv', 'si.store_inventory_id', '=', 'si_inv.id')
                ->join('stores as s', 'si_inv.store_id', '=', 's.id')
                ->join('users as u', 'u.store_id', '=', 's.id')
                ->select(
                    'si.id AS store_inventory_item_id',
                    'si.product_name',
                    'si.product_code',
                    'si.count_1',
                    'si.count_2',
                    's.name AS store_name',
                    's.location AS store_location',
                    'si_inv.status AS inventory_status',
                    'si_inv.id AS store_inventory_id' // Add this line to include store_inventory_id
                )
                ->where('u.id', $userId)
                ->get();
    
            return response()->json([
                'status' => $inventoryStatus,
                'items' => $items,
            ]);
        }
    
        // If the inventory is closed or not in a valid state, return an empty list
        return response()->json([
            'status' => $inventoryStatus,
            'items' => [],
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
            DB::table('store_inventory_items')
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
            DB::table('store_inventory_items')
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
     * Check if all items have completed count_1 or count_2.
     */
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