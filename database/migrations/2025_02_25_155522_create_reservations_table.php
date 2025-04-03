<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->string('statut'); // en attente, confirmé, annulé
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('reservable_id');
            $table->string('reservable_type');
            $table->timestamps();
            
            $table->index(['reservable_id', 'reservable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};