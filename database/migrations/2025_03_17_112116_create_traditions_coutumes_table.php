<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraditionsCoutumesTable extends Migration
{
    public function up()
    {
        Schema::create('traditions_coutumes', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 255);
            $table->text('resume');
            $table->longText('contenu');
            $table->foreignId('categorie_id')->constrained('categories_traditions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('traditions_coutumes');
    }
}
