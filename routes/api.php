<?php

use App\Http\Controllers\API\APIInventoryController; // Assurez-vous d'importer le bon contrôleur
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\API\AuthController;


Route::patch('/inventory/{storeInventoryId}/status', [APIInventoryController::class, 'updateInventoryStatus']);

Route::get('/inventory', [APIInventoryController::class, 'getUserInventory']);
Route::patch('/inventory/{id}/count1', [APIInventoryController::class, 'updateCount1']);
Route::patch('/inventory/{id}/count2', [APIInventoryController::class, 'updateCount2']);


Route::middleware('auth:sanctum')->get('inventory', [APIInventoryController::class, 'getUserInventory']);

// Route pour récupérer les éléments d'inventaire de l'utilisateur connecté (authentification Sanctum)
Route::middleware([EnsureFrontendRequestsAreStateful::class, 'auth:sanctum'])->get('inventory', [APIInventoryController::class, 'getUserInventory']);

// Route pour obtenir les informations de l'utilisateur connecté (authentification Sanctum)
Route::middleware([EnsureFrontendRequestsAreStateful::class, 'auth:sanctum'])->get('user', [AuthController::class, 'user']);

// Route pour se connecter (connexion avec email et mot de passe)
Route::post('/login', [AuthController::class, 'login']);

// Route pour se déconnecter (invalider le token)
Route::middleware([EnsureFrontendRequestsAreStateful::class, 'auth:sanctum'])->post('/logout', [AuthController::class, 'logout']);


