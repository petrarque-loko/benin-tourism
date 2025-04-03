<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRaisonAnnulationToReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->text('raison_annulation')->nullable()->after('statut');
            $table->unsignedBigInteger('guide_id')->nullable()->after('user_id');
            $table->foreign('guide_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['guide_id']);
            $table->dropColumn(['raison_annulation', 'guide_id']);
        });
    }
}