<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeyFromSpecificinventory extends Migration
{
    public function up()
    {
        Schema::table('specificinventory', function (Blueprint $table) {
            // Supprimer la clé étrangère correcte
            $table->dropForeign('fk_inventory_id');
        });
    }

    public function down()
    {
        // Recréer la contrainte de clé étrangère si nécessaire
        Schema::table('specificinventory', function (Blueprint $table) {
            $table->foreign('Abr_Store')->references('Abr_Store')->on('stores')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
