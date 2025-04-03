<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->text('contenu');
            $table->integer('note')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('commentable_id');
            $table->string('commentable_type');
            $table->timestamps();
            
            $table->index(['commentable_id', 'commentable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};
