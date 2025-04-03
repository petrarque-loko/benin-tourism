@extends('layouts.proprietaire')

@section('title', 'Réservations pour ' . $chambre->nom)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">
                Réservations pour {{ $chambre->nom }}
            </h1>
            <a href="{{ route('proprietaire.hebergements.reservations.index', $hebergement->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à l'hébergement
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Informations sur la chambre
                </h3>
            </div>
            <div class="px-4 py-5 sm:px-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Nom</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $chambre->nom }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $chambre->type }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Capacité</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $chambre->capacite }} personne(s)</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Prix</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $chambre->prix }} €</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg" x-data="{ 
            activeTab: 'upcoming',
            reservations: {{ Illuminate\Support\Js::from($reservations) }},
            get filteredReservations() {
                const today = new Date();
                today.setHours(0, 0, 0, 0); // Normaliser l'heure à minuit
                
                if (this.activeTab === 'upcoming') {
                    return this.reservations.filter(res => new Date(res.date_debut) >= today);
                } else if (this.activeTab === 'past') {
                    return this.reservations.filter(res => new Date(res.date_fin) < today);
                }
                return this.reservations;
            },
            formatDate(dateString) {
                if (!dateString) return '';
                const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
                return new Date(dateString).toLocaleDateString('fr-FR', options);
            },
            getStatusClass(status) {
                const classes = {
                    'approuvé': 'bg-green-100 text-green-800',
                    'en_attente': 'bg-yellow-100 text-yellow-800',
                    'annulée': 'bg-red-100 text-red-800',
                    'confirmé': 'bg-green-100 text-green-800',
                    'en attente': 'bg-yellow-100 text-yellow-800',
                    'annulé': 'bg-red-100 text-red-800'
                };
                return classes[status.toLowerCase()] || 'bg-gray-100 text-gray-800';
            }
        }">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Liste des réservations
                    </h3>
                    <div class="flex space-x-2">
                        <button @click="activeTab = 'upcoming'" 
                                :class="{'bg-indigo-600 text-white': activeTab === 'upcoming', 'bg-gray-200 text-gray-700': activeTab !== 'upcoming'}"
                                class="px-3 py-1 rounded text-sm font-medium">
                            À venir
                        </button>
                        <button @click="activeTab = 'past'" 
                                :class="{'bg-indigo-600 text-white': activeTab === 'past', 'bg-gray-200 text-gray-700': activeTab !== 'past'}"
                                class="px-3 py-1 rounded text-sm font-medium">
                            Passées
                        </button>
                        <button @click="activeTab = 'all'" 
                                :class="{'bg-indigo-600 text-white': activeTab === 'all', 'bg-gray-200 text-gray-700': activeTab !== 'all'}"
                                class="px-3 py-1 rounded text-sm font-medium">
                            Toutes
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date de début
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date de fin
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-if="filteredReservations.length === 0">
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm font-medium text-gray-500">
                                    Aucune réservation trouvée
                                </td>
                            </tr>
                        </template>
                        
                        <template x-for="reservation in filteredReservations" :key="reservation.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <span x-text="reservation.id"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span x-text="reservation.user.nom + ' ' + reservation.user.prenom"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span x-text="formatDate(reservation.date_debut)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span x-text="formatDate(reservation.date_fin)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusClass(reservation.statut)" 
                                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                          x-text="reservation.statut">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a :href="`{{ route('proprietaire.reservations.show', '') }}/${reservation.id}`" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        Détails
                                    </a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="text-sm text-gray-700">
                    <span x-text="filteredReservations.length"></span> réservations affichées sur 
                    <span x-text="reservations.length"></span> au total
                </div>
            </div>
        </div>
    </div>
</div>
@endsection