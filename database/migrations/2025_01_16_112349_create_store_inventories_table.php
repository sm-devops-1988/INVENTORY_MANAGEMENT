<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreInventoriesTable extends Migration
{
    
    // Example migration for store_inventories

public function up()
{
    Schema::create('store_inventories', function (Blueprint $table) {
        $table->id();
        $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
        $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');  // Lien avec stores
        $table->string('status');
        $table->timestamps();
   // Ajouter une contrainte d'unicité
   $table->unique(['inventory_id', 'store_id']);

    });
}



    public function down()
    {
        Schema::dropIfExists('store_inventories');
    }
}
