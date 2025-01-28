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
    Schema::table('stores', function (Blueprint $table) {
        $table->string('Abr_Store')->nullable(); // Ajoutez cette ligne pour le champ Abr_Store
    });
}

public function down()
{
    Schema::table('stores', function (Blueprint $table) {
        $table->dropColumn('Abr_Store');
    });
}
};
