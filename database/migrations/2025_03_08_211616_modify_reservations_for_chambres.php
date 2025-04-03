<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Ajoutons chambre_id comme champ supplémentaire
            // Nous gardons reservable_type et reservable_id pour d'autres types de réservations
            $table->unsignedBigInteger('chambre_id')->nullable()->after('guide_id');
            $table->foreign('chambre_id')->references('id')->on('chambres');
            
            // Ajoutons nombre_personnes
            $table->integer('nombre_personnes')->default(1)->after('raison_annulation');
            
            // Ajoutons un champ pour le statut de paiement
            $table->string('statut_paiement')->default('en_attente'); // en_attente, payé, remboursé
            $table->string('reference_paiement')->nullable(); // Pour référence KKiaPay
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
