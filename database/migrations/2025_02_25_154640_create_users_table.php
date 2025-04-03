<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('telephone')->nullable();
            $table->text('adresse')->nullable();
            $table->foreignId('role_id')->constrained();
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('activation_token')->nullable();
                });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};