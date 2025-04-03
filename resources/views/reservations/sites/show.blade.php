@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="reservationDetails()">
    <div class="max-w-4xl mx-auto">
        <!-- Carte principale -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden transition-all duration-500 transform hover:shadow-2xl">
            <!-- En-tête avec animation -->
            <div class="relative overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 h-24 p-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Détails de la Réservation
                        </h2>
                        
                        <div 
                            class="rounded-full px-4 py-1 text-sm font-bold uppercase tracking-wider shadow transition-all duration-300"
                            :class="{
                                'bg-yellow-500 text-yellow-900': '{{ $reservation->statut }}' === 'en_attente',
                                'bg-green-500 text-green-900': '{{ $reservation->statut }}' === 'approuvé',
                                'bg-red-500 text-red-900': '{{ $reservation->statut }}' === 'rejeté',
                                'bg-gray-500 text-gray-900': '{{ $reservation->statut }}' === 'annulé',
                                'bg-blue-500 text-blue-900': '{{ $reservation->statut }}' === 'terminé'
                            }"
                        >
                            <span class="flex items-center">
                                <svg x-show="'{{ $reservation->statut }}' === 'en_attente'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <svg x-show="'{{ $reservation->statut }}' === 'approuvé'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <svg x-show="'{{ $reservation->statut }}' === 'rejeté'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <svg x-show="'{{ $reservation->statut }}' === 'annulé'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                <svg x-show="'{{ $reservation->statut }}' === 'terminé'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ ucfirst($reservation->statut) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Vagues décoratives -->
                <div class="absolute -bottom-1 left-0 right-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
                        <path fill="#ffffff" fill-opacity="1" d="M0,64L48,80C96,96,192,128,288,128C384,128,480,96,576,85.3C672,75,768,85,864,96C960,107,1056,117,1152,112C1248,107,1344,85,1392,74.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Contenu principal avec onglets -->
            <div class="p-6 pt-2">
                <div x-data="{ activeTab: 'details' }">
                    <!-- Navigation des onglets -->
                    <div class="border-b border-gray-200 mb-6">
                        <ul class="flex -mb-px">
                            <li class="mr-1">
                                <button @click="activeTab = 'details'" :class="{ 'border-b-2 border-blue-500 text-blue-600': activeTab === 'details' }" class="inline-block py-4 px-4 text-sm font-medium text-center transition-all duration-200 hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Informations
                                </button>
                            </li>
                            <li class="mr-1">
                                <button @click="activeTab = 'guide'" :class="{ 'border-b-2 border-blue-500 text-blue-600': activeTab === 'guide' }" class="inline-block py-4 px-4 text-sm font-medium text-center transition-all duration-200 hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Guide
                                </button>
                            </li>
                            <li class="mr-1">
                                <button @click="activeTab = 'actions'" :class="{ 'border-b-2 border-blue-500 text-blue-600': activeTab === 'actions' }" class="inline-block py-4 px-4 text-sm font-medium text-center transition-all duration-200 hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Actions
                                </button>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Contenu des onglets -->
                    <div class="space-y-6">
                        <!-- Onglet Détails -->
                        <div x-show="activeTab === 'details'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Informations de la réservation -->
                                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Informations de la Réservation
                                    </h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span class="text-gray-600">Numéro de Réservation</span>
                                            <span class="font-semibold">{{ $reservation->id }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100" x-data="{ copied: false }">
                                            <span class="text-gray-600">Date de Début</span>
                                            <span class="font-semibold">{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span class="text-gray-600">Date de Fin</span>
                                            <span class="font-semibold">{{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span class="text-gray-600">Durée</span>
                                            <span class="font-semibold">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ \Carbon\Carbon::parse($reservation->date_debut)->diffInDays(\Carbon\Carbon::parse($reservation->date_fin)) }} jour(s)
                                                </span>
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center py-2">
                                            <span class="text-gray-600">Date de Création</span>
                                            <span class="font-semibold text-sm">{{ $reservation->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Barre de progression dynamique -->
                                    <div class="mt-6">
                                        <div class="relative pt-1">
                                            <div class="flex mb-2 items-center justify-between">
                                                <div>
                                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                                        Progression
                                                    </span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-xs font-semibold inline-block text-blue-600" x-text="progressText"></span>
                                                </div>
                                            </div>
                                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                                                <div :style="`width: ${progressPercentage}%`" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500 transition-width duration-1000 ease-in-out"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Détails du Site/Activité -->
                                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Détails du Site/Activité
                                    </h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span class="text-gray-600">Nom</span>
                                            <span class="font-semibold">
                                                @if($reservation->reservable)
                                                    {{ $reservation->reservable->nom }}
                                                @else
                                                    <span class="text-gray-400 italic">Site supprimé</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span class="text-gray-600">Localisation</span>
                                            <span class="font-semibold">
                                                @if($reservation->reservable)
                                                    {{ $reservation->reservable->localisation }}
                                                @else
                                                    <span class="text-gray-400 italic">Site supprimé</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span class="text-gray-600">Catégorie</span>
                                            <span class="font-semibold">
                                                @if($reservation->reservable && $reservation->reservable->categorie)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        {{ $reservation->reservable->categorie->nom }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 italic">Catégorie supprimée</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Image du site avec effet hover -->
                                    @if($reservation->reservable && $reservation->reservable->medias)
                                        <div class="mt-4 overflow-hidden rounded-lg shadow-sm transform transition duration-300 hover:scale-105 hover:shadow-md">
                                            <img src="{{ asset('storage/' . $reservation->reservable->medias->first()->url) }}" alt="{{ $reservation->reservable->nom }}" class="w-full h-40 object-cover">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                           
                            
                            <!-- Raison d'annulation -->
                            @if($reservation->statut == 'rejeté' || $reservation->statut == 'annulé')
                                <div class="bg-white rounded-lg shadow-md p-6 mt-6 border-l-4 border-red-500">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Raison de l'Annulation
                                    </h4>
                                    <div class="bg-red-50 text-red-700 p-4 rounded-md">
                                        {{ $reservation->raison_annulation ?? 'Aucune raison spécifiée' }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Onglet Guide -->
                        <div x-show="activeTab === 'guide'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Détails du Guide</h4>
                                <div class="flex flex-col md:flex-row md:items-start">
                                    <div class="flex-shrink-0 mb-4 md:mb-0">
                                        <img src="{{ $reservation->guide->avatar ?? asset('images/3.jpeg') }}" 
                                             class="h-32 w-32 rounded-full object-cover border-4 border-blue-500 shadow-lg" 
                                             alt="Avatar du guide">
                                    </div>
                                    <div class="md:ml-6 flex-grow">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $reservation->guide->prenom }} {{ $reservation->guide->nom }}</h3>
                                        <p class="text-gray-600 mb-2">{{ $reservation->guide->email }}</p>
                                        
                                        <div class="flex items-center mb-4">
                                            @php
                                                $noteGuide = $reservation->guide->commentaires->avg('note') ?? 0;
                                            @endphp
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $i <= $noteGuide ? 'text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                                <span class="ml-2 text-sm text-gray-600">({{ number_format($noteGuide, 1) }}/5 basé sur {{ $reservation->guide->commentaires->count() }} avis)</span>
                                            </div>
                                        </div>
                                        
                                        @if($reservation->guide->bio)
                                            <div class="mb-4">
                                                <h4 class="text-md font-semibold text-gray-700 mb-2">Biographie</h4>
                                                <p class="text-gray-600">{{ $reservation->guide->bio }}</p>
                                            </div>
                                        @endif
                                        
                                        <div class="mb-4">
                                            <h4 class="text-md font-semibold text-gray-700 mb-2">Spécialités</h4>
                                            <div class="flex flex-wrap gap-2">
                                                @forelse($reservation->guide->specialites ?? [] as $specialite)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">{{ $specialite->nom }}</span>
                                                @empty
                                                    <span class="text-gray-500 italic">Aucune spécialité spécifiée</span>
                                                @endforelse
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <a href="{{ route('users.show', $reservation->guide->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                Voir le profil complet
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Onglet Actions amélioré -->
                <div x-show="activeTab === 'actions'" 
                     x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="opacity-0 transform translate-x-2" 
                     x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Actions disponibles
                        </h4>

                        @if(in_array($reservation->statut, ['en_attente', 'approuvé']))
                            <div class="flex flex-col md:flex-row md:justify-between gap-4 mb-6">
                                <a href="{{ route('touriste.reservations.sites.edit', $reservation->id) }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Modifier la Réservation
                                </a>

                                <button 
                                    @click="openCancelModal = true"
                                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Annuler la Réservation
                                </button>
                            </div>

                            <!-- Modale de confirmation pour annulation -->
                            <div x-show="openCancelModal" 
                                 class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" 
                                 x-transition:enter="transition ease-out duration-300" 
                                 x-transition:enter-start="opacity-0" 
                                 x-transition:enter-end="opacity-100" 
                                 x-transition:leave="transition ease-in duration-200" 
                                 x-transition:leave-start="opacity-100" 
                                 x-transition:leave-end="opacity-0">
                                <div class="bg-white rounded-lg p-6 max-w-md w-full">
                                    <h5 class="text-lg font-semibold mb-4">Confirmer l'annulation</h5>
                                    <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir annuler cette réservation ?</p>
                                    <div class="flex justify-end gap-4">
                                        <button @click="openCancelModal = false" 
                                                class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                                            Non, revenir
                                        </button>
                                        <form action="{{ route('touriste.reservations.sites.cancel', $reservation->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                Oui, annuler
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @elseif($reservation->statut == 'terminé')
                            <div class="mb-6">
                                <a href="{{ route('users.show', $reservation->guide->id) }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Évaluer le Guide
                                </a>
                            </div>
                        @else
                            <div class="bg-gray-100 p-6 rounded-md text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-600">Aucune action disponible pour une réservation {{ $reservation->statut }}.</p>
                            </div>
                        @endif

                        <!-- Section Actions supplémentaires -->
                        <div class="mt-6 p-4 bg-blue-50 rounded-md">
                            <h5 class="text-md font-semibold text-gray-800 mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Actions supplémentaires
                            </h5>

                            <div class="flex flex-wrap gap-3 mt-2">
                                <a href="{{ route('touriste.reservations.sites.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                    Voir toutes mes réservations
                                </a>

                                <button onclick="window.print()" 
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Imprimer les détails
                                </button>

                                <button 
                                    @click="showShareOptions = !showShareOptions"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                    Partager
                                </button>
                            </div>

                            <!-- Options de partage (cachées par défaut) -->
                            <div x-show="showShareOptions" 
                                 x-transition:enter="transition ease-out duration-200" 
                                 x-transition:enter-start="opacity-0 transform scale-95" 
                                 x-transition:enter-end="opacity-100 transform scale-100" 
                                 x-transition:leave="transition ease-in duration-100" 
                                 x-transition:leave-start="opacity-100 transform scale-100" 
                                 x-transition:leave-end="opacity-0 transform scale-95" 
                                 class="mt-3 p-3 bg-white border border-gray-200 rounded-md shadow-sm">
                                <p class="text-sm text-gray-600 mb-2">Partager via :</p>
                                <div class="flex space-x-3">
                                    <a href="#" class="text-blue-600 hover:text-blue-800">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-blue-400 hover:text-blue-600">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.232 8.232 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-green-600 hover:text-green-800">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.023.047 1.351.058 3.807.058h.468c2.456 0 2.784-.011 3.807-.058.975-.045 1.504-.207 1.857-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.047-1.023.058-1.351.058-3.807v-.468c0-2.456-.011-2.784-.058-3.807-.045-.975-.207-1.504-.344-1.857-.182-.466-.399-.8-.748-1.15-.35-.35-.683-.566-1.15-.748-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection