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
        Schema::create('chambres', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->nullable(); // Numéro de chambre
            $table->string('nom');
            $table->text('description');
            $table->string('type_chambre'); // standard, luxe, suite, familiale, etc.
            $table->integer('capacite'); // Nombre maximum de personnes
            $table->decimal('prix', 10, 2);
            $table->boolean('est_disponible')->default(true);
            $table->boolean('est_visible')->default(true);
            $table->unsignedBigInteger('hebergement_id');
            $table->foreign('hebergement_id')->references('id')->on('hebergements');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chambres');
    }
};
