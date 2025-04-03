<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hebergements', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description');
            $table->string('adresse');
            $table->decimal('prix_min', 10, 2);
            $table->decimal('prix_max', 10, 2);
            $table->boolean('disponibilite')->default(true);
            $table->foreignId('proprietaire_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hebergements');
    }
};
