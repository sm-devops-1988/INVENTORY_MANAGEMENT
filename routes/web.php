<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StoreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StoreInventoryItemController;
use App\Http\Controllers\StoreInventoryController;



Route::get('/StoreInventoryItem', [StoreInventoryItemController::class, 'index'])->name('StoreInventoryItem.index');


Route::get('/store-inventory-items/export', [StoreInventoryItemController::class, 'export'])->name('StoreInventoryItem.export');

//use App\Http\Controllers\APIInventoryController;
//use App\Http\Controllers\API\AuthController;


// Routes pour l'authentification
Auth::routes();

// Routes pour l'accueil
Route::get('/', function () {
    return redirect()->route('home');
});

// Routes protégées par le middleware 'auth'
Route::middleware(['auth'])->group(function () {
    
    // Page d'accueil
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Routes pour la gestion des utilisateurs
    Route::resource('users', UserController::class);
    Route::get('users/by_store', [UserController::class, 'byStore'])->name('users.by_store');
    //Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    //Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    //Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    //Route::post('/profile', [UserController::class, 'update'])->name('profile.update');
    
    // Routes pour la gestion des magasins
    Route::resource('stores', StoreController::class);

   

  // Display the import form
Route::get('/inventory/{inventoryId}/import', [InventoryController::class, 'showImportForm'])
->name('inventories.import');

// Handle the import for all stores
Route::post('/inventory/{inventoryId}/import', [InventoryController::class, 'import'])
->name('inventory.import');

// Import Specific Routes
Route::prefix('importSpecific')->group(function () {
    // Show the import form
    Route::get('/{inventoryId}/import', [ImportSpecificController::class, 'showImportForm'])
         ->name('importSpecific.showImportForm');

    // Handle the import
    Route::post('/{inventoryId}/import', [ImportSpecificController::class, 'import'])
         ->name('importSpecific.import');
});

    // Routes pour les inventaires
    Route::resource('inventories', InventoryController::class);
    Route::get('inventories/create', [InventoryController::class, 'create'])->name('inventories.create');
    Route::post('/inventories/{inventoryId}/import', [InventoryController::class, 'import'])->name('inventories.import');
    Route::post('inventories/{inventoryId}/import-products', [InventoryController::class, 'importProducts'])->name('inventories.importProducts');
    Route::post('inventories/{inventoryId}/associate-store', [InventoryController::class, 'associateStore'])->name('inventories.associateStore');
    Route::post('inventories/{inventoryId}/store-inventories/{storeInventoryId}/import-products', [InventoryController::class, 'importProducts'])->name('inventories.importProducts');
    Route::post('inventories/{inventoryId}/store-inventories/{storeInventoryId}/add-product', [InventoryController::class, 'addProductToStoreInventory'])->name('inventories.addProductToStoreInventory');
    Route::get('inventories/{inventoryId}/associate-store', [InventoryController::class, 'showAssociateStoreForm'])->name('inventories.showAssociateStoreForm');

    // Routes pour les StoreInventory
    Route::resource('storeinventories', StoreInventoryController::class);
    Route::get('store-inventory/{storeInventoryId}', [StoreInventoryController::class, 'show'])->name('storeinventory.show');
    Route::get('storeinventories/create', [StoreInventoryController::class, 'create'])->name('storeinventories.create');
    Route::post('storeinventories', [StoreInventoryController::class, 'store'])->name('storeinventories.store');

    // Routes pour les StoreInventoryItem
    Route::resource('storeinventoryitems', StoreInventoryItemController::class);
    Route::get('storeinventory/{storeInventoryId}/items', [StoreInventoryItemController::class, 'index'])->name('storeinventoryitems.index');
    Route::get('storeinventory/{storeInventoryId}/items/create', [StoreInventoryItemController::class, 'create'])->name('storeinventoryitems.create');
    Route::post('storeinventory/{storeInventoryId}/items', [StoreInventoryItemController::class, 'store'])->name('storeinventoryitems.store');
    Route::get('storeinventory/items/{itemId}', [StoreInventoryItemController::class, 'show'])->name('storeinventoryitems.show');
    Route::get('storeinventory/items/{itemId}/edit', [StoreInventoryItemController::class, 'edit'])->name('storeinventoryitems.edit');
    Route::put('storeinventory/items/{itemId}', [StoreInventoryItemController::class, 'update'])->name('storeinventoryitems.update');
    Route::delete('storeinventory/items/{itemId}', [StoreInventoryItemController::class, 'destroy'])->name('storeinventoryitems.destroy');

    // Synchroniser les comptages depuis l'application mobile
   // Route::post('sync-inventory', [ApiCountingController::class, 'syncInventory']);
});

// Routes pour gérer le profil utilisateur (protégé par auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::post('/profile', [UserController::class, 'update'])->name('profile.update');
});

// Routes par défaut - redirection vers la page d'accueil
Route::get('/', function () {
    return redirect()->route('home');
});

