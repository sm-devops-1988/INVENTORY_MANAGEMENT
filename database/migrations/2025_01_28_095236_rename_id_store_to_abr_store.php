<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameIdStoreToAbrStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Exemple de migration pour la table specificinventory
        Schema::table('specificinventory', function (Blueprint $table) {
            $table->foreign('abr_store')
          ->references('id')
          ->on('stores')
          ->onDelete('cascade')
          ->onUpdate('cascade');
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('specificinventory', function (Blueprint $table) {
            // Revenir à la colonne originale
            $table->renameColumn('abr_store', 'id_store');
        });
    }

    
}
