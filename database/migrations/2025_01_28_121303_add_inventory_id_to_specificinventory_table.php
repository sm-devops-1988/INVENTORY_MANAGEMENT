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
        // Ajouter la colonne inventory_id
        $table->unsignedBigInteger('inventory_id')->after('id'); // Vous pouvez ajuster la position de la colonne
    });
}

public function down()
{
    Schema::table('specificinventory', function (Blueprint $table) {
        // Supprimer la colonne inventory_id
        $table->dropColumn('inventory_id');
    });
}
};
