<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Hebergement;
use App\Models\Chambre;
use App\Models\Reservation;
use App\Models\Commentaire;
use App\Models\User; // Ajout de l'import correct
use App\Policies\HebergementPolicy;
use App\Policies\ChambrePolicy;
use App\Policies\ReservationPolicy;
use App\Policies\PaiementPolicy;
use App\Policies\CommentairePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Hebergement::class => HebergementPolicy::class,
        Chambre::class => ChambrePolicy::class,
        Reservation::class => ReservationPolicy::class,
        Commentaire::class => CommentairePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Définir les gates pour les paiements (car ils ne sont pas liés à un modèle)
        Gate::define('initier-paiement', [PaiementPolicy::class, 'initier']);
        Gate::define('confirmer-paiement', [PaiementPolicy::class, 'confirmer']);
        Gate::define('voir-historique-paiement', [PaiementPolicy::class, 'historique']);

        // Définir d'autres gates si nécessaire
        Gate::define('view-statistics', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('access-admin-panel', function (User $user) {
            return $user->isAdmin();
        });
        
        // Gate pour les notifications
        Gate::define('receive-reservation-notifications', function (User $user) {
            return $user->isProprietaire();
        });
        
        // Gates pour le catalogue public
        Gate::define('view-public-catalogue', function (?User $user = null) {
            return true; // Accessible à tous, même aux utilisateurs non connectés
        });
        
        // Gate pour le filtrage des chambres
        Gate::define('filter-chambres', function (?User $user = null) {
            return true; // Accessible à tous, même aux utilisateurs non connectés
        });
    }
}