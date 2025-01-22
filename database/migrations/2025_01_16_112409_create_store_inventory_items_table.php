<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreInventoryItemsTable extends Migration
{
    public function up()
    {
        Schema::create('store_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_inventory_id')->constrained()->onDelete('cascade'); // Lien avec la table store_inventories
            $table->string('product_name');
            $table->string('product_code')->unique();
            $table->decimal('count_1', 10, 2)->default(0); // Premier comptage
            $table->decimal('count_2', 10, 2)->default(0); // Deuxième comptage
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('store_inventory_items');
    }
}
