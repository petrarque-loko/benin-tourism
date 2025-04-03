<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('circuit_site_touristique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circuit_id')->constrained()->onDelete('cascade');
            $table->foreignId('sites_touristique_id')->constrained()->onDelete('cascade');
            $table->integer('ordre')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circuit_site_touristique');
    }
};