<?php
namespace App\Http\Controllers;

use App\Models\OpenInventory;
use App\Models\Inventory;
use App\Models\Store;
use App\Models\StoreInventory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OpenInventoryItemsExport;

class OpenInventoryItemController extends Controller
{
    public function index(Request $request)
    {
        $inventories = Inventory::where('type', 'libre')->get();
        $statuses = StoreInventory::distinct('status')->pluck('status');
        $stores = Store::all();

        $selectedInventoryId = $request->input('inventory_id');
        $selectedStatus = $request->input('status');
        $selectedStoreId = $request->input('store_id');
        $selectedDate = $request->input('date');

        $items = OpenInventory::query()
            ->when($selectedInventoryId, function ($query) use ($selectedInventoryId) {
                return $query->whereHas('storeInventory', function ($query) use ($selectedInventoryId) {
                    $query->where('inventory_id', $selectedInventoryId);
                });
            })
            ->when($selectedStatus, function ($query) use ($selectedStatus) {
                return $query->whereHas('storeInventory', function ($query) use ($selectedStatus) {
                    $query->where('status', $selectedStatus);
                });
            })
            ->when($selectedStoreId, function ($query) use ($selectedStoreId) {
                return $query->whereHas('storeInventory.store', function ($query) use ($selectedStoreId) {
                    $query->where('id', $selectedStoreId);
                });
            })
            ->when($selectedDate, function ($query) use ($selectedDate) {
                return $query->whereDate('created_at', $selectedDate);
            })
            ->get();

        return view('open_inventory.index', compact(
            'items',
            'inventories',
            'statuses',
            'stores',
            'selectedInventoryId',
            'selectedStatus',
            'selectedStoreId',
            'selectedDate'
        ));
    }

    public function export(Request $request)
    {
        $selectedInventoryId = $request->input('inventory_id');
        $selectedStatus = $request->input('status');
        $selectedStoreId = $request->input('store_id');
        $selectedDate = $request->input('date');

        $items = OpenInventory::query()
            ->when($selectedInventoryId, function ($query) use ($selectedInventoryId) {
                return $query->whereHas('storeInventory', function ($q) use ($selectedInventoryId) {
                    $q->where('inventory_id', $selectedInventoryId);
                });
            })
            ->when($selectedStatus, function ($query, $selectedStatus) {
                return $query->whereHas('storeInventory', function ($q) use ($selectedStatus) {
                    $q->where('status', $selectedStatus);
                });
            })
            ->when($selectedStoreId, function ($query, $selectedStoreId) {
                return $query->whereHas('storeInventory.store', function ($q) use ($selectedStoreId) {
                    $q->where('id', $selectedStoreId);
                });
            })
            ->when($selectedDate, function ($query, $selectedDate) {
                return $query->whereDate('created_at', $selectedDate);
            })
            ->get();

        return Excel::download(new OpenInventoryItemsExport($items), 'open_inventory_items.xlsx');
    }
}
