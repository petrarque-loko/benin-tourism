@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br  to-indigo-50 py-12" x-data="{
    chambre: {
        id: {{ $chambre->id }},
        nom: '{{ $chambre->nom }}',
        description: '{{ addslashes($chambre->description) }}',
        prix: {{ $chambre->prix }},
        capacite: {{ $chambre->capacite }},
        type_chambre: '{{ $chambre->type_chambre }}'
    },
    hebergement: {
        id: {{ $chambre->hebergement->id }},
        nom: '{{ $chambre->hebergement->nom }}'
    },
    dateDebut: '{{ old('date_debut', date('Y-m-d')) }}',
    dateFin: '{{ old('date_fin', date('Y-m-d', strtotime('+1 day'))) }}',
    nombrePersonnes: {{ old('nombre_personnes', 1) }},
    showDetails: false,
    get nombreJours() {
        const debut = new Date(this.dateDebut);
        const fin = new Date(this.dateFin);
        const diffTime = Math.abs(fin - debut);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays || 1;
    },
    get prixTotal() {
        return this.nombreJours * this.chambre.prix;
    },
    formatDate(dateString) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('fr-FR', options);
    }
}">
    <div class="max-w-5xl mx-auto">
        <!-- Header avec effet parallaxe léger -->
        <div class="relative h-64 rounded-t-2xl overflow-hidden shadow-lg transform -translate-y-2 hover:translate-y-0 transition-transform duration-300" 
             x-bind:style="'background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.6)), url(https://images.unsplash.com/photo-1566073771259-6a8506099945?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1080&q=80) center/cover no-repeat'">
            <div class="absolute inset-0 bg-gradient-to-r from-pink-900/60 to-indigo-900/60"></div>
            <div class="absolute inset-x-0 bottom-0 p-8">bg-orange-100
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-12 bg-blue-400 rounded"></div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Réservation</h1>
                        <p class="text-blue-100 mt-1" x-text="hebergement.nom"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-2xl shadow-xl p-0 mb-12">
            <!-- Tabs navigation -->
            <div class="flex border-b border-gray-200">
                <button class="flex-1 py-4 px-6 text-center border-b-2 border-blue-900 font-medium text-green-600">
                    <span class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Détails de la réservation
                    </span>
                </button>
            </div>

            <!-- Main content -->
            <div class="p-8">
                <!-- Room info card with hover effect -->
                <div class="mb-8 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md"
                     x-bind:class="{'ring-2 ring-blue-400': showDetails}"
                     @click="showDetails = !showDetails">
                    <div class="flex flex-col md:flex-row">
                        <!-- Left side: Room image placeholder -->
                        <div class="w-full md:w-1/3 bg-gradient-to-br from-blue-500 to-indigo-500 text-white p-6 flex flex-col justify-between">
                            <div>
                                <div class="inline-block px-3 py-1 rounded-full bg-white/20 text-sm font-medium backdrop-blur-sm" x-text="chambre.type_chambre"></div>
                                <h3 class="mt-4 text-xl font-bold" x-text="chambre.nom"></h3>
                            </div>
                            <div class="mt-10">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span x-text="chambre.capacite + ' personne' + (chambre.capacite > 1 ? 's' : '')"></span>
                                </div>
                                <div class="flex items-center mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="chambre.prix + ' €/nuit'"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right side: Room details -->
                        <div class="w-full md:w-2/3 p-6">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-800">Description</h3>
                                <button class="text-blue-500 focus:outline-none" @click.stop="showDetails = !showDetails">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" 
                                         x-bind:class="{'rotate-180': showDetails}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-3 text-gray-600" x-show="showDetails" x-transition x-text="chambre.description"></p>
                            <p class="mt-3 text-gray-600" x-show="!showDetails" x-text="chambre.description.length > 150 ? chambre.description.substring(0, 150) + '...' : chambre.description"></p>
                            
                            <div class="mt-6 flex justify-end">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-lime-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Disponible maintenant
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('error'))
                    <div class="mb-8 relative bg-red-50 border-l-4 border-red-400 p-4 rounded-r" role="alert" 
                         x-data="{ show: true }" x-show="show" x-transition>
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button @click="show = false" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Booking form with interactive elements -->
                <form method="POST" action="{{ route('touriste.reservations.hebergements.store') }}" class="space-y-8">
                    @csrf
                    <input type="hidden" name="chambre_id" x-bind:value="chambre.id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Date selector column -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Dates du séjour
                            </h3>
                            
                            <div class="relative" x-data="{ focusStart: false, focusEnd: false }">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="relative">
                                        <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">Date d'arrivée</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <input id="date_debut" type="date" 
                                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-12 py-3 sm:text-sm border-gray-300 rounded-lg shadow-sm transition-all duration-200"
                                                x-bind:class="{'ring-2 ring-blue-400 border-blue-400': focusStart}" 
                                                name="date_debut" 
                                                x-model="dateDebut"
                                                @focus="focusStart = true" 
                                                @blur="focusStart = false"
                                                x-on:change="if(dateFin < dateDebut) dateFin = dateDebut"
                                                min="{{ date('Y-m-d') }}" 
                                                required>
                                        </div>
                                        @error('date_debut')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="relative">
                                        <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">Date de départ</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <input id="date_fin" type="date" 
                                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-12 py-3 sm:text-sm border-gray-300 rounded-lg shadow-sm transition-all duration-200"
                                                x-bind:class="{'ring-2 ring-blue-400 border-blue-400': focusEnd}" 
                                                name="date_fin" 
                                                x-model="dateFin"
                                                @focus="focusEnd = true" 
                                                @blur="focusEnd = false"
                                                x-bind:min="dateDebut" 
                                                required>
                                        </div>
                                        @error('date_fin')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Date summary -->
                                <div class="flex items-center mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100 text-sm text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Du <span class="font-medium" x-text="formatDate(dateDebut)"></span> au <span class="font-medium" x-text="formatDate(dateFin)"></span></span>
                                </div>
                            </div>

                            <div class="py-6">
                                <label for="nombre_personnes" class="block text-sm font-medium text-gray-700 mb-1">Nombre de personnes</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <select id="nombre_personnes" 
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-12 py-3 sm:text-sm border-gray-300 rounded-lg shadow-sm"
                                        name="nombre_personnes" 
                                        x-model="nombrePersonnes"
                                        required>
                                        <template x-for="i in chambre.capacite" :key="i">
                                            <option x-bind:value="i" x-text="i + ' personne' + (i > 1 ? 's' : '')"></option>
                                        </template>
                                    </select>
                                    @error('nombre_personnes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Booking summary column -->
                        <div class="bg-gradient-to-br from-lime-500 to-lime-600 text-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-bold flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Résumé de votre réservation
                                </h3>
                                
                                <div class="mt-6 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span>Chambre</span>
                                        <span class="font-medium" x-text="chambre.nom"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span>Durée</span>
                                        <span class="font-medium" x-text="nombreJours + ' nuit' + (nombreJours > 1 ? 's' : '')"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span>Voyageurs</span>
                                        <span class="font-medium" x-text="nombrePersonnes + ' personne' + (nombrePersonnes > 1 ? 's' : '')"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span>Prix / nuit</span>
                                        <span class="font-medium" x-text="chambre.prix + ' €'"></span>
                                    </div>
                                    
                                    <div class="pt-4 mt-4 border-t border-white/20">
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg">Total</span>
                                            <span class="text-xl font-bold" x-text="prixTotal + ' €'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-black/10 to-black/5 p-6">
                                <div class="flex items-center text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span>Annulation gratuite jusqu'à 48h avant</span>
                                </div>
                                <div class="flex items-center text-sm mt-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <span>Paiement sécurisé</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex flex-col md:flex-row justify-end space-y-4 md:space-y-0 md:space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('touriste.reservations.hebergements.show', $chambre->hebergement_id) }}" 
                           class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-white  bg-yellow-600 hover:bg-stone-700  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Retour
                        </a>
                        <button type="submit" 
                                class="inline-flex justify-center items-center px-8 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-blue-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 transform hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Confirmer la réservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection