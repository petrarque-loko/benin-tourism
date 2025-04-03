@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br  to-gray-100 py-12" 
     x-data="{ 
        isSubmitting: false, 
        reason: '', 
        showDetails: true,
        showForm: true,
        highlightedField: null
     }"
     x-init="
        setTimeout(() => {
            showDetails = true;
            setTimeout(() => showForm = true, 300);
        }, 300);
     ">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
        <!-- Header avec animation d'entrée -->
        <div class="transform transition-all duration-500 ease-in-out" 
             :class="{'translate-y-0 opacity-100': true, 'translate-y-4 opacity-0': false}">
            <h1 class="text-center text-3xl font-extrabold text-gray-900 mb-6 flex items-center justify-center">
                <span class="bg-clip-text text-transparent bg-gradient-to-r  bg-rose-900 to-red-400">
                    Annulation de réservation
                </span>
            </h1>
        </div>

        <!-- Card principale avec shadow et animation -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden transform transition-all duration-500 ease-in-out hover:shadow-2xl">
            <!-- Header avec effet de couleur -->
            <div class="bg-gradient-to-r bg-rose-900 text-white rounded-lg shadow-md hover:bg-rose-700  p-6">
                <div class="flex items-center">
                    <svg class="h-8 w-8 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <h2 class="text-2xl font-bold">Confirmer l'annulation</h2>
                </div>
                <p class="mt-1 text-red-100 text-sm">Veuillez remplir les informations ci-dessous pour finaliser votre demande</p>
            </div>

            <div class="p-6 sm:p-8">
                <!-- Message d'erreur avec animation -->
                @if(session('error'))
                    <div class="bg-red-50 border-l-4 bg-rose-900 text-white rounded-lg shadow-md hover:bg-rose-700 p-4 mb-6 rounded-md shadow-sm transform transition-all duration-300 hover:scale-102 hover:shadow-md" 
                         role="alert"
                         x-data="{ show: true }"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p>{{ session('error') }}</p>
                                <button @click="show = false" class="text-sm text-red-800 font-medium hover:underline mt-1">Fermer</button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Avertissement avec animation au survol -->
                <div class="bg-amber-50 border-l-4 border-amber-500 rounded-md p-4 mb-8 shadow-sm transform transition-all duration-300 hover:shadow-md"
                     x-data="{ pulse: false }"
                     @mouseenter="pulse = true"
                     @mouseleave="pulse = false"
                     :class="{ 'animate-pulse': pulse }">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-amber-800">Attention</h3>
                            <div class="mt-1 text-sm text-amber-700">
                                <p>Cette action est <span class="font-semibold">irréversible</span>. Une fois la réservation annulée, vous ne pourrez plus la restaurer.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section des détails avec animation d'apparition -->
                <div class="mb-8 transform transition-all duration-500 ease-out"
                     x-show="showDetails"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Détails de la réservation
                    </h2>
                    
                    <div class="bg-gray-50 p-6 rounded-lg shadow-inner border border-gray-100 transform transition-all duration-300 hover:shadow-md">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                            <div class="transform transition-all duration-300 hover:translate-x-1 hover:text-gray-900">
                                <p class="text-gray-500 text-sm font-medium mb-1">Hébergement</p>
                                <p class="font-semibold text-lg">{{ $reservation->reservable->hebergement->nom }}</p>
                            </div>
                            
                            <div class="transform transition-all duration-300 hover:translate-x-1 hover:text-gray-900">
                                <p class="text-gray-500 text-sm font-medium mb-1">Chambre</p>
                                <p class="font-semibold text-lg">{{ $reservation->reservable->nom }}</p>
                            </div>
                            
                            <div class="transform transition-all duration-300 hover:translate-x-1 hover:text-gray-900">
                                <p class="text-gray-500 text-sm font-medium mb-1">Dates</p>
                                <p class="font-semibold">
                                    <span class="inline-flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $reservation->date_debut->format('d/m/Y') }}
                                    </span>
                                    <span class="mx-2">-</span>
                                    <span class="inline-flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $reservation->date_fin->format('d/m/Y') }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="transform transition-all duration-300 hover:translate-x-1 hover:text-gray-900">
                                <p class="text-gray-500 text-sm font-medium mb-1">Nombre de personnes</p>
                                <p class="font-semibold inline-flex items-center">
                                    <svg class="h-5 w-5 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                    </svg>
                                    {{ $reservation->nombre_personnes }}
                                </p>
                            </div>
                            
                            <div class="transform transition-all duration-300 hover:translate-x-1 hover:text-gray-900">
                                <p class="text-gray-500 text-sm font-medium mb-1">Prix total</p>
                                <p class="font-semibold text-lg inline-flex items-center">
                                    <svg class="h-5 w-5 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-gray-900">{{ $reservation->prixTotal() }} €</span>
                                </p>
                            </div>
                            
                            <div class="transform transition-all duration-300 hover:translate-x-1 hover:text-gray-900">
                                <p class="text-gray-500 text-sm font-medium mb-1">Statut</p>
                                <p class="font-medium">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        {{ $reservation->statut === 'confirmee' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200' }}
                                        shadow-sm transition-all duration-300 hover:shadow">
                                        <svg class="h-4 w-4 mr-1 {{ $reservation->statut === 'confirmee' ? 'text-green-500' : 'text-yellow-500' }}" 
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            @if($reservation->statut === 'confirmee')
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            @else
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            @endif
                                        </svg>
                                        {{ $reservation->statut === 'confirmee' ? 'Confirmée' : 'En attente' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire d'annulation avec animation -->
                <div class="transform transition-all duration-500 ease-out"
                     x-show="showForm"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0">
                    
                    <form action="{{ route('touriste.reservations.hebergements.cancel', $reservation->id) }}" method="POST" 
                          @submit.prevent="isSubmitting = true; $event.target.submit()">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="raison_annulation" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <svg class="h-4 w-4 mr-1 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Raison de l'annulation <span class="text-red-500 ml-1">*</span>
                            </label>
                            
                            <div class="relative">
                                <select id="raison_annulation" 
                                        name="raison_annulation" 
                                        x-model="reason" 
                                        required
                                        @focus="highlightedField = 'reason'"
                                        @blur="highlightedField = null"
                                        class="appearance-none block w-full px-4 py-3 rounded-lg border transition-all duration-300 ease-in-out
                                               focus:ring-4 focus:outline-none focus:ring-opacity-50
                                               text-base text-gray-700"
                                        :class="{'border-gray-300 bg-white focus:border-indigo-500 focus:ring-indigo-200': highlightedField !== 'reason',
                                                'border-indigo-400 bg-indigo-50 ring-2 ring-indigo-200': highlightedField === 'reason'}">
                                    <option value="" disabled selected>Sélectionnez une raison</option>
                                    <option value="Changement de plans">Changement de plans</option>
                                    <option value="Maladie ou problème de santé">Maladie ou problème de santé</option>
                                    <option value="Problème de transport">Problème de transport</option>
                                    <option value="Raison professionnelle">Raison professionnelle</option>
                                    <option value="Autre hébergement trouvé">Autre hébergement trouvé</option>
                                    <option value="Autre">Autre raison</option>
                                </select>
                                
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-5 w-5 transition-transform duration-300" 
                                         :class="{'transform rotate-180': highlightedField === 'reason'}"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div x-show="reason === 'Autre'"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0" 
                                 class="mt-4">
                                <label for="raison_detail" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <svg class="h-4 w-4 mr-1 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Précisez <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <textarea id="raison_detail" 
                                            name="raison_detail" 
                                            rows="3" 
                                            x-bind:required="reason === 'Autre'"
                                            @focus="highlightedField = 'detail'"
                                            @blur="highlightedField = null"
                                            placeholder="Veuillez préciser la raison de votre annulation..."
                                            class="block w-full px-4 py-3 border text-base transition-all duration-300 ease-in-out
                                                rounded-lg resize-none focus:outline-none focus:ring-4 focus:ring-opacity-50"
                                            :class="{'border-gray-300 focus:border-indigo-500 focus:ring-indigo-200': highlightedField !== 'detail',
                                                    'border-indigo-400 bg-indigo-50 ring-2 ring-indigo-200': highlightedField === 'detail'}"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('touriste.reservations.hebergements.show', $reservation->id) }}" 
                               class="relative inline-flex items-center px-5 py-2.5 rounded-lg shadow-sm text-sm font-medium
                                     bg-white text-gray-700 border border-gray-300
                                     transition-all duration-300 ease-in-out
                                      text-white  bg-yellow-600 hover:bg-stone-700  hover:border-gray-400 hover:-translate-y-1
                                     focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                               x-data="{ hover: false }"
                               @mouseenter="hover = true"
                               @mouseleave="hover = false">
                                <span class="flex items-center transition-all duration-300" :class="{ '-translate-x-1': hover }">
                                    <svg class="h-5 w-5 mr-2 text-gray-500 transition-all duration-300" 
                                         :class="{ 'transform -translate-x-1': hover }"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Retour
                                </span>
                            </a>
                            
                            <button type="submit" 
                                    x-bind:disabled="isSubmitting || !reason" 
                                    x-bind:class="{
                                        'opacity-50 cursor-not-allowed bg-red-400': isSubmitting || !reason,
                                        'bg-red-600 hover:bg-red-700 hover:-translate-y-1': !isSubmitting && reason
                                    }"
                                    class="relative inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm
                                         text-sm font-medium text-white transition-all duration-300 transform
                                         focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <span x-show="!isSubmitting" class="flex items-center">
                                    <svg class="h-5 w-5 mr-2 transition-all duration-300" 
                                         :class="{ 'animate-bounce': reason && !isSubmitting }"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Confirmer l'annulation
                                </span>
                                <span x-show="isSubmitting" class="flex items-center">
                                    <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Traitement...
                                </span>
                                <!-- Onde d'effet au clic -->
                                <span class="absolute inset-0 rounded-lg overflow-hidden" aria-hidden="true">
                                    <span x-cloak 
                                          x-data="{ buttonClicked: false }" 
                                          @click="buttonClicked = true; setTimeout(() => buttonClicked = false, 1000)" 
                                          x-show="buttonClicked"
                                          x-transition:enter="transition ease-out duration-300"
                                          x-transition:enter-start="opacity-0 scale-0"
                                          x-transition:enter-end="opacity-30 scale-100"
                                          x-transition:leave="transition ease-in duration-300"
                                          x-transition:leave-start="opacity-30 scale-100"
                                          x-transition:leave-end="opacity-0 scale-0"
                                          class="absolute inset-0 bg-white rounded-lg transform origin-center"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            </div>
        </div>
    </div>
</div>
@endsection