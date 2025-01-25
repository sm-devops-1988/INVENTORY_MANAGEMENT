<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('store_inventory_items', function (Blueprint $table) {
            $table->integer('onhand')->default(0); // Ajoutez la colonne Onhand
        });
    }
    
    public function down()
    {
        Schema::table('store_inventory_items', function (Blueprint $table) {
            $table->dropColumn('onhand'); // Supprimez la colonne Onhand en cas de rollback
        });
    }
};
