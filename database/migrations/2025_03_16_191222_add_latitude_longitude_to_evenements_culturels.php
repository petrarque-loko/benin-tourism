<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatitudeLongitudeToEvenementsCulturels extends Migration
{
    public function up()
    {
        Schema::table('evenements_culturels', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable();  // Peut être vide si non défini
            $table->decimal('longitude', 10, 7)->nullable(); // Peut être vide si non défini
        });
    }

    public function down()
    {
        Schema::table('evenements_culturels', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
}
