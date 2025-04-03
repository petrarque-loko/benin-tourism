@extends('layouts.guide')

@section('content')
<style>
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .slide-in {
        animation: slideIn 0.4s ease-out;
    }
    @keyframes slideIn {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>

<div class="container mx-auto px-4 py-8" x-data="dashboardData()">
    <!-- En-tête -->
    <header class="mb-8 fade-in">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-indigo-800 mb-2">Tableau de Bord du Guide</h1>
                <p class="text-gray-600">Un aperçu de vos activités et performances.</p>
            </div>
            <button @click="refreshData()" class="mt-4 md:mt-0 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center transition-all duration-300">
                <i class="fas fa-sync-alt mr-2" :class="{'animate-spin': isLoading}"></i>
                Rafraîchir
            </button>
        </div>
    </header>

    <!-- Grille principale -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Statistiques générales -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-xl shadow-md p-6 card-hover fade-in" x-show="!isLoading">
                <div class="flex items-center mb-4">
                    <div class="bg-indigo-100 p-3 rounded-lg">
                        <i class="fas fa-chart-line text-indigo-600 text-xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800 ml-3">Statistiques Générales</h2>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <p class="text-gray-600">Total Réservations</p>
                        <span class="font-bold text-indigo-600" x-text="totalReservations"></span>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-gray-600">Réservations Approuvées</p>
                        <span class="font-bold text-green-600" x-text="reservationsApprouvees"></span>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-gray-600">Commentaires Reçus</p>
                        <span class="font-bold text-yellow-600" x-text="commentaires"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-gray-600">Note Moyenne</p>
                        <div class="flex items-center">
                            <span class="font-bold text-indigo-600 mr-2" x-text="`${noteMoyenne}/5`"></span>
                            <div class="text-yellow-500">
                                <template x-for="i in 5" :key="i">
                                    <i :class="i <= noteMoyenne ? 'fas fa-star' : 'far fa-star'" class="text-sm"></i>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prochaines réservations et derniers commentaires -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Prochaines réservations -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover fade-in" x-show="!isLoading">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 ml-3">Prochaines Réservations</h2>
                    </div>
                </div>
                <div class="space-y-4">
                    <template x-for="(reservation, index) in prochaines" :key="index">
                        <div class="border-b py-3 last:border-b-0 slide-in" :style="`animation-delay: ${index * 100}ms`">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-800" x-text="reservation.nom"></p>
                                    <p class="text-sm text-gray-600" x-text="formatDate(reservation.date_debut)"></p>
                                </div>
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full" x-text="reservation.statut"></span>
                            </div>
                        </div>
                    </template>
                    <div x-show="prochaines.length === 0" class="text-center py-4 text-gray-500">
                        Aucune réservation à venir
                    </div>
                </div>
            </div>

            <!-- Derniers commentaires -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover fade-in" x-show="!isLoading">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <i class="fas fa-comment-dots text-yellow-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 ml-3">Derniers Commentaires</h2>
                    </div>
                </div>
                <div class="space-y-4">
                    <template x-for="(commentaire, index) in derniersCommentaires" :key="index">
                        <div class="border-b py-3 last:border-b-0 slide-in" :style="`animation-delay: ${index * 100}ms`">
                            <div class="flex justify-between items-center">
                                <div class="flex items-start">
                                    <div class="bg-indigo-100 text-indigo-800 font-bold rounded-full h-10 w-10 flex items-center justify-center">
                                        <span x-text="commentaire.user.name.charAt(0)"></span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-800" x-text="commentaire.user.name"></p>
                                        <p class="text-sm text-gray-600" x-text="commentaire.contenu"></p>
                                    </div>
                                </div>
                                <div class="text-yellow-500 flex">
                                    <template x-for="i in 5" :key="i">
                                        <i :class="i <= commentaire.note ? 'fas fa-star' : 'far fa-star'" class="text-sm"></i>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="derniersCommentaires.length === 0" class="text-center py-4 text-gray-500">
                        Aucun commentaire récent
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicateur de chargement -->
    <div x-show="isLoading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-indigo-600"></div>
    </div>
</div>

<script>
function dashboardData() {
    return {
        isLoading: false,
        totalReservations: {{ $totalReservations }},
        reservationsApprouvees: {{ $reservationsApprouvees }},
        commentaires: {{ $commentaires }},
        noteMoyenne: {{ number_format($noteMoyenne, 1) }},
        prochaines: [
            @foreach($prochaines as $reservation)
            {
                nom: "{{ $reservation->reservable->nom ?? 'Site' }}",
                date_debut: "{{ $reservation->date_debut }}",
                statut: "{{ $reservation->statut ?? 'En attente' }}"
            },
            @endforeach
        ],
        derniersCommentaires: [
            @foreach($derniersCommentaires as $commentaire)
            {
                user: { name: "{{ $commentaire->user->nom . ' ' . $commentaire->user->prenom }}" },
                contenu: "{{ $commentaire->contenu }}",
                note: {{ $commentaire->note ?? 0 }}
            },
            @endforeach
        ],
        refreshData() {
            this.isLoading = true;
            setTimeout(() => {
                this.isLoading = false;
            }, 1000); // Simulation de chargement (remplacez par une vraie requête si nécessaire)
        },
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' });
        }
    };
}
</script>
@endsection