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
        Schema::table('hebergements', function (Blueprint $table) {
            $table->unsignedBigInteger('type_hebergement_id')->after('proprietaire_id')->nullable();
            $table->foreign('type_hebergement_id')->references('id')->on('types_hebergement');
            
            // Ajoutons aussi des coordonnées pour l'emplacement
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('ville')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hebergements', function (Blueprint $table) {
            //
        });
    }
};
