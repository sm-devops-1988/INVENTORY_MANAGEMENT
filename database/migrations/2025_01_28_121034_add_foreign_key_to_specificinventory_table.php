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
        Schema::table('specificinventory', function (Blueprint $table) {
            // Ajouter la clé étrangère
            $table->foreign('inventory_id')
                  ->references('id')
                  ->on('inventories')
                  ->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('specificinventory', function (Blueprint $table) {
            // Supprimer la clé étrangère
            $table->dropForeign(['inventory_id']);
        });
    }
};
