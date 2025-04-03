@extends('layouts.guide')

@section('content')
<div x-data="{
    reservation: {
        statut: '{{ $reservation->statut }}',
        raison: '{{ $reservation->raison_annulation ?? '' }}'
    },
    showRejectModal: false,
    rejectReason: '',
    statusColors: {
        'en_attente': 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'approuvé': 'bg-green-100 text-green-800 border-green-300',
        'rejeté': 'bg-red-100 text-red-800 border-red-300',
        'annulé': 'bg-gray-100 text-gray-800 border-gray-300',
        'terminé': 'bg-blue-100 text-blue-800 border-blue-300'
    },
    statusIcons: {
        'en_attente': 'clock',
        'approuvé': 'check-circle',
        'rejeté': 'x-circle',
        'annulé': 'slash',
        'terminé': 'flag'
    },
    getStatusColor() {
        return this.statusColors[this.reservation.statut];
    },
    getStatusIcon() {
        return this.statusIcons[this.reservation.statut];
    },
    approveReservation() {
        // Animation avant soumission du formulaire
        this.$refs.approveBtn.classList.add('animate-pulse');
        setTimeout(() => {
            this.$refs.approveForm.submit();
        }, 500);
    }
}" 
class="container mx-auto px-4 py-8 font-sans">
    <!-- Carte principale avec animation d'entrée -->
    <div 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
        
        <!-- En-tête de la carte -->
        <div class="relative overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 h-16"></div>
            <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center px-6">
                <div class="flex justify-between items-center w-full">
                    <h1 class="text-3xl font-bold text-white">Détails de la Réservation</h1>
                    <div 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        class="flex items-center space-x-2 rounded-full px-4 py-1.5 border-2"
                        :class="getStatusColor()">
                        <svg class="w-5 h-5" x-show="getStatusIcon() === 'clock'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <svg class="w-5 h-5" x-show="getStatusIcon() === 'check-circle'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <svg class="w-5 h-5" x-show="getStatusIcon() === 'x-circle'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <svg class="w-5 h-5" x-show="getStatusIcon() === 'slash'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                        <svg class="w-5 h-5" x-show="getStatusIcon() === 'flag'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                        </svg>
                        <span class="font-bold">{{ ucfirst($reservation->statut) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="p-6">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Informations du Touriste -->
                <div 
                    x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-600 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold dark:text-white">Informations du Touriste</h2>
                    </div>
                    <div class="space-y-3 text-gray-700 dark:text-gray-200">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="font-medium">{{ $reservation->user->nom }} {{ $reservation->user->prenom }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <p class="font-medium">{{ $reservation->user->email }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <p class="font-medium">{{ $reservation->user->telephone ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Détails de la Réservation -->
                <div 
                    x-transition:enter="transition ease-out duration-300 delay-300"
                    x-transition:enter-start="opacity-0 transform translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-600 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold dark:text-white">Détails de la Réservation</h2>
                    </div>
                    <div class="space-y-3 text-gray-700 dark:text-gray-200">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="font-medium">{{ $reservation->reservable->nom ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="font-medium">Du {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="font-medium">Du {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="font-medium">Au {{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</p>
                        </div>
                        
                        <!-- Raison d'annulation ou de rejet (conditionnelle) -->
                        <template x-if="reservation.statut === 'rejeté' || reservation.statut === 'annulé'">
                            <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-800">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-red-500 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <p class="font-medium text-red-700 dark:text-red-300">Raison : <span x-text="reservation.raison"></span></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Actions pour le Guide (conditionnelle) -->
            <div 
                x-transition:enter="transition ease-out duration-300 delay-500"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-show="reservation.statut === 'en_attente'"
                class="mt-8 space-y-4">
                <h2 class="text-xl font-semibold dark:text-white mb-4">Actions</h2>
                <div class="flex space-x-4">
                    <form x-ref="approveForm" method="POST" action="{{ route('guide.reservations.updateStatus', $reservation->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="statut" value="approuvé">
                        <button 
                            x-ref="approveBtn"
                            @click.prevent="approveReservation()"
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center space-x-2 focus:ring-4 focus:ring-green-300">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Approuver</span>
                        </button>
                    </form>
                    
                    <button 
                        @click="showRejectModal = true"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center space-x-2 focus:ring-4 focus:ring-red-300">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Rejeter</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Rejet -->
    <div 
        x-show="showRejectModal" 
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div 
            x-show="showRejectModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            @click.away="showRejectModal = false"
            class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md shadow-2xl">
            <h3 class="text-xl font-bold mb-4 dark:text-white">Raison du Rejet</h3>
            <form method="POST" action="{{ route('guide.reservations.updateStatus', $reservation->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="statut" value="rejeté">
                
                <div class="mb-4">
                    <textarea 
                        x-model="rejectReason" 
                        name="raison_annulation" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition duration-200"
                        rows="4"
                        placeholder="Veuillez expliquer la raison du rejet..."
                        required></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button 
                        type="button" 
                        @click="showRejectModal = false"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white rounded-lg transition duration-200">
                        Annuler
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="!rejectReason.trim()">
                        Confirmer le Rejet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection