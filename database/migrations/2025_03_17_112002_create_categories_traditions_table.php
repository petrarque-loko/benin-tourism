<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTraditionsTable extends Migration
{
    public function up()
    {
        Schema::create('categories_traditions', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories_traditions');
    }
}