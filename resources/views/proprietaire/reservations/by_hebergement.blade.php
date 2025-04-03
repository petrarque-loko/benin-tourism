@extends('layouts.proprietaire')

@section('title', 'Réservations pour ' . $hebergement->nom)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Réservations pour {{ $hebergement->nom }}</h1>
            <a href="{{ route('proprietaire.hebergements.show', $hebergement->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à l'hébergement
            </a>
        </div>
        
        <!-- Info sur l'hébergement -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Détails de l'hébergement</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $hebergement->adresse }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $hebergement->type }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Capacité totale</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $hebergement->chambres->sum('capacite') }} personnes</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Nombre de chambres</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $hebergement->chambres->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Liste des chambres avec liens -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Chambres disponibles</h3>
            </div>
            <div class="border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    @foreach($hebergement->chambres as $chambre)
                    <li>
                        <a href="{{ route('proprietaire.hebergements.chambres.reservations.index', [$hebergement->id, $chambre->id]) }}" 
                           class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-indigo-600 truncate">{{ $chambre->nom }}</p>
                                    <p class="mt-1 text-sm text-gray-500">Capacité: {{ $chambre->capacite }} personnes</p>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $chambre->reservations->count() }} réservations
                                    </p>
                                </div>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Liste des réservations avec Alpine.js -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg" x-data="{ 
                activeTab: 'all',
                search: '',
                get filteredReservations() {
                    return this.filterReservations({{ Illuminate\Support\Js::from($reservations) }});
                },
                filterReservations(reservations) {
                    return reservations.filter(reservation => {
                        const matchesSearch = this.search === '' || 
                            (reservation.user.nom + ' ' + reservation.user.prenom).toLowerCase().includes(this.search.toLowerCase()) ||
                            reservation.chambre.nom.toLowerCase().includes(this.search.toLowerCase()) ||
                            reservation.id.toString().includes(this.search);
                        
                        const matchesStatus = this.activeTab === 'all' || 
                            (this.activeTab === 'upcoming' && new Date(reservation.date_debut) > new Date()) || 
                            (this.activeTab === 'current' && new Date(reservation.date_debut) <= new Date() && new Date(reservation.date_fin) >= new Date()) ||
                            (this.activeTab === 'past' && new Date(reservation.date_fin) < new Date());
                        
                        return matchesSearch && matchesStatus;
                    });
                }
            }">
            <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                    <div class="ml-4 mt-2">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Liste des réservations</h3>
                    </div>
                    <div class="ml-4 mt-2 flex-shrink-0">
                        <div class="relative">
                            <input type="text" x-model="search" placeholder="Rechercher..." 
                                  class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Onglets de filtrage -->
                <div class="mt-4 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="activeTab = 'all'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'all', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'all'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Toutes
                        </button>
                        <button @click="activeTab = 'upcoming'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'upcoming', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'upcoming'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            À venir
                        </button>
                        <button @click="activeTab = 'current'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'current', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'current'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            En cours
                        </button>
                        <button @click="activeTab = 'past'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'past', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'past'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Passées
                        </button>
                    </nav>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chambre</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Personnes</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paiement</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-if="filteredReservations.length === 0">
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucune réservation trouvée
                                </td>
                            </tr>
                        </template>
                        <template x-for="reservation in filteredReservations" :key="reservation.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="reservation.id"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div x-text="reservation.user.nom + ' ' + (reservation.user.prenom || '')"></div>
                                    <div class="text-xs text-gray-400" x-text="reservation.user.email"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="reservation.chambre.nom"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div x-text="new Date(reservation.date_debut).toLocaleDateString('fr-FR')"></div>
                                    <div x-text="new Date(reservation.date_fin).toLocaleDateString('fr-FR')"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="reservation.nombre_personnes"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span x-show="reservation.statut === 'approuvé'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Confirmée
                                    </span>
                                    <span x-show="reservation.statut === 'en_attente'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>
                                    <span x-show="reservation.statut === 'annulée'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Annulée
                                    </span>
                                    <span x-show="reservation.statut && !['approuvé', 'en_attente', 'annulée'].includes(reservation.statut)" 
                                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800" 
                                          x-text="reservation.statut"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span x-show="reservation.statut_paiement === 'payé'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Payé
                                    </span>
                                    <span x-show="reservation.statut_paiement === 'en_attente'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>
                                    <span x-show="reservation.statut_paiement === 'échoué'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Échoué
                                    </span>
                                    <span x-show="reservation.statut_paiement && !['payé', 'en_attente', 'échoué'].includes(reservation.statut_paiement)" 
                                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800" 
                                          x-text="reservation.statut_paiement"></span>
                                    <span x-show="!reservation.statut_paiement" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        N/A
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
        </div>
    </div>
</div>
@endsection