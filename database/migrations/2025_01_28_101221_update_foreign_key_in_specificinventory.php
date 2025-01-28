<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForeignKeyInSpecificinventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('specificinventory', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère existante
            $table->dropForeign(['id_store']);
            
            // Supprimer la colonne id_store si elle existe (optionnel)
            $table->dropColumn('id_store');

            // Ajouter la nouvelle contrainte de clé étrangère sur abr_store
            $table->foreign('abr_store')
                  ->references('abr_store')  // Référence à abr_store dans stores
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
            // Supprimer la contrainte de clé étrangère
            $table->dropForeign(['abr_store']);
            
            // Ajouter à nouveau la colonne id_store si besoin
            $table->foreign('id_store')
                  ->references('id')
                  ->on('stores')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }
}
