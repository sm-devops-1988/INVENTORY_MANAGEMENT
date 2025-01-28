<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToSpecificinventory extends Migration
{
    public function up()
    {
        // Ajouter une nouvelle contrainte de clé étrangère sur 'Abr_Store'
        Schema::table('specificinventory', function (Blueprint $table) {
            $table->foreign('Abr_Store')->references('Abr_Store')->on('stores')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        // Supprimer la contrainte de clé étrangère si nécessaire
        Schema::table('specificinventory', function (Blueprint $table) {
            $table->dropForeign(['Abr_Store']);
        });
    }
}
