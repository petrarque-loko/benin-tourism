@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br to-gray-100 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8 animate-fade-in">
            <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r bg-orange-500 tracking-tight">
                Mes réservations d'hébergements
            </h1>
        </div>

        <!-- Success Message with Enhanced Animation -->
        @if(session('success'))
            <div 
                x-data="{ show: true }" 
                x-show="show" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                x-init="setTimeout(() => show = false, 5000)" 
                class="bg-gradient-to-r from-green-100 to-green-50 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-lg shadow-md" 
                role="alert">
                <div class="flex items-center">
                    <div class="mr-4">
                        <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-700 hover:text-green-900 transition-colors">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- Empty State with Improved Design -->
        @if($reservations->isEmpty())
            <div class="bg-white rounded-2xl shadow-2xl p-10 text-center space-y-6 border border-gray-100">
                <div class="flex justify-center mb-6">
                    <div class="bg-indigo-50 rounded-full p-6">
                        <svg class="h-20 w-20 text-indigo-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucune réservation trouvée</h3>
                <p class="text-gray-600 mb-6">Vous n'avez pas encore effectué de réservation d'hébergement.</p>
                <a href="{{ route('hebergements.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                    Découvrir des hébergements
                    <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        @else
            <!-- Main Container with Enhanced Alpine.js -->
            <div x-data="{
                activeTab: 'all',
                today: new Date().toISOString().split('T')[0],
                reservations: [
                    @foreach($reservations as $reservation)
                    {
                        id: {{ $reservation->id }},
                        hebergement: '{{ $reservation->reservable->hebergement->nom ?? 'Non disponible' }}',
                        chambre: '{{ $reservation->reservable->nom ?? 'Non spécifié' }}',
                        chambre_type: '{{ $reservation->reservable->type_chambre ?? 'Inconnu' }}',
                        date_debut: '{{ $reservation->date_debut->format('Y-m-d') }}',
                        date_fin: '{{ $reservation->date_fin->format('Y-m-d') }}',
                        date_debut_format: '{{ $reservation->date_debut->format('d/m/Y') }}',
                        date_fin_format: '{{ $reservation->date_fin->format('d/m/Y') }}',
                        statut: '{{ $reservation->statut }}',
                        prix: {{ $reservation->prixTotal() ?? $reservation->reservable->prix }},
                        hebergement_id: {{ $reservation->reservable->hebergement_id ?? 0 }}
                    },
                    @endforeach
                ]
            }">
                <!-- Enhanced Tabs with Gradient and Shadow -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6">
                    <div class="flex -mb-px border-b border-gray-200">
                        <button 
                            @click="activeTab = 'all'" 
                            :class="{'bg-gradient-to-r bg-yellow-500 text-black-600 border-indigo-500': activeTab === 'all', 'text-black-500 bg-lime-500': activeTab !== 'all'}" 
                            class="flex-1 py-4 px-4 text-center font-semibold transition-all duration-300 ease-in-out">
                            Toutes les réservations
                        </button>
                        <button 
                            @click="activeTab = 'upcoming'" 
                            :class="{'bg-gradient-to-r  bg-yellow-500 text-black-600 border-indigo-500': activeTab === 'upcoming', 'text-black-500 bg-lime-500': activeTab !== 'upcoming'}" 
                            class="flex-1 py-4 px-4 text-center font-semibold transition-all duration-300 ease-in-out">
                            À venir
                        </button>
                        <button 
                            @click="activeTab = 'past'" 
                            :class="{'bg-gradient-to-r  bg-yellow-500 text-black-600 border-indigo-500': activeTab === 'past', 'text-black-500 bg-lime-500': activeTab !== 'past'}" 
                            class="flex-1 py-4 px-4 text-center font-semibold transition-all duration-300 ease-in-out">
                            Passées
                        </button>
                        <button 
                            @click="activeTab = 'canceled'" 
                            :class="{'bg-gradient-to-r  bg-yellow-500 text-black-600 border-indigo-500': activeTab === 'canceled', 'text-black-500 bg-lime-500': activeTab !== 'canceled'}" 
                            class="flex-1 py-4 px-4 text-center font-semibold transition-all duration-300 ease-in-out">
                            Annulées
                        </button>
                    </div>
                </div>

                <!-- Reservations Table with Enhanced Styling -->
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-600 uppercase tracking-wider">
                                        Hébergement / Chambre
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-600 uppercase tracking-wider">
                                        Dates
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-600 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-600 uppercase tracking-wider">
                                        Prix Total
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-indigo-600 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <template x-for="reservation in reservations" :key="reservation.id">
                                    <tr 
                                        x-show="
                                            (activeTab === 'all') || 
                                            (activeTab === 'upcoming' && (reservation.statut !== 'annulee' && reservation.date_debut >= today)) ||
                                            (activeTab === 'past' && (reservation.statut !== 'annulee' && reservation.date_fin < today)) ||
                                            (activeTab === 'canceled' && reservation.statut === 'annulee')
                                        " 
                                        class="hover:bg-gray-50 transition-colors duration-200">
                                        <!-- Reservation details remain the same as the original -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900" x-text="reservation.hebergement"></div>
                                                    <div class="text-sm text-gray-500">
                                                        <span x-text="reservation.chambre"></span> 
                                                        (<span x-text="reservation.chambre_type"></span>)
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <span x-text="reservation.date_debut_format"></span> à <span x-text="reservation.date_fin_format"></span>
                                            </div>
                                            <div class="text-sm text-gray-500" x-text="calculateNights(reservation.date_debut, reservation.date_fin) + ' nuit(s)'"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <template x-if="reservation.statut === 'confirmee'">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Confirmée
                                                </span>
                                            </template>
                                            <template x-if="reservation.statut === 'en_attente'">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    En attente
                                                </span>
                                            </template>
                                            <template x-if="reservation.statut === 'annulee'">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Annulée
                                                </span>
                                            </template>
                                            <template x-if="reservation.statut === 'terminee'">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Terminée
                                                </span>
                                            </template>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span x-text="new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(reservation.prix)"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a :href="'/reservations/hebergements/' + reservation.id" class="bg-orange-700 text-white px-4 py-2 rounded-lg hover:bg-orange-800 transition-colors">
                                                Détails
                                            </a>
                                            <template x-if="reservation.statut !== 'annulee' && reservation.statut !== 'terminee' && reservation.date_debut > today">
                                                <span>
                                                    <a :href="'/reservations/hebergements/' + reservation.id + '/edit'" class="text-indigo-600 hover:text-indigo-900 mr-3 hover:underline transition-colors">
                                                        Modifier
                                                    </a>
                                                    <a :href="'/reservations/hebergements/' + reservation.id + '/confirm-cancel'" class="text-red-600 hover:text-red-900 hover:underline transition-colors">
                                                        Annuler
                                                    </a>
                                                </span>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination with Enhanced Styling -->
                    <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200">
                        {{ $reservations->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function calculateNights(dateDebut, dateFin) {
        const start = new Date(dateDebut);
        const end = new Date(dateFin);
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays;
    }
</script>
@endsection