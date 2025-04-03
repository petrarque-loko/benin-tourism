<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvenementsCulturelsTable extends Migration
{
    public function up()
    {
        Schema::create('evenements_culturels', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->string('lieu');
            $table->foreignId('categorie_id')->constrained('categories_evenements');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evenements_culturels');
    }
}
