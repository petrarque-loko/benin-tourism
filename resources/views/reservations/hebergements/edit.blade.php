@extends('layouts.app')

@section('content')
<div class="py-12 bg-gradient-to-br  via-blue-50 to-purple-50 min-h-screen" x-data="{ showPageAnimation: false }" x-init="setTimeout(() => showPageAnimation = true, 100)">
    <div 
        class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 transition-all duration-700 ease-in-out transform" 
        :class="showPageAnimation ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
    >
        <!-- Bouton de retour avec animation hover -->
        <div class="mb-8" x-data="{ hover: false }">
            <a 
                href="{{ route('touriste.reservations.hebergements.show', $reservation->id) }}" 
                class="group inline-flex items-center text-sm font-medium text-yellow-600 transition-all duration-300 ease-in-out"
                :class="hover ? 'text-indigo-800 -translate-x-1' : ''"
                @mouseenter="hover = true" 
                @mouseleave="hover = false"
            >
                <svg 
                    class="mr-2 h-5 w-5 transition-transform duration-300 ease-in-out" 
                    :class="hover ? '-translate-x-1' : ''" 
                    xmlns="http://www.w3.org/2000/svg" 
                    viewBox="0 0 20 20" 
                    fill="currentColor"
                >
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                <span class="relative">
                    Retour aux détails
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-300 group-hover:w-full"></span>
                </span>
            </a>
        </div>

        <!-- Carte principale -->
        <div 
            class="bg-white shadow-xl rounded-xl overflow-hidden transition-all duration-500 border border-indigo-100"
            x-data="{ hoverCard: false }"
            @mouseenter="hoverCard = true"
            @mouseleave="hoverCard = false"
            :class="hoverCard ? 'shadow-indigo-200' : 'shadow-gray-200'"
        >
            <!-- En-tête de la carte -->
            <div class="px-6 py-6 border-b border-indigo-100 bg-gradient-to-r from-lime-600 to-lime-500 text-white">
                <h3 class="text-xl font-bold leading-6">Modifier ma réservation</h3>
                <p class="mt-2 text-indigo-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7z" />
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 1h8v10H6V5z" clip-rule="evenodd" />
                    </svg>
                    <span>Chambre: <span class="font-medium">{{ $reservation->reservable->nom }}</span> ({{ $reservation->reservable->type_chambre }})</span>
                </p>
            </div>

            <!-- Message d'erreur avec animation -->
            @if(session('error'))
                <div 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-init="setTimeout(() => show = false, 5000)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="bg-red-50 border-l-4 border-red-500 text-red-700 p-5 mx-6 mt-6 rounded-md shadow-sm" 
                    role="alert"
                >
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500 mr-4 animate-pulse" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-grow">
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                        <button 
                            type="button" 
                            @click="show = false" 
                            class="ml-auto bg-red-100 text-red-500 rounded-full p-1 hover:bg-red-200 transition-colors duration-200"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <div class="p-8">
                <!-- Encadré d'information -->
                <div 
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 mb-8 border border-indigo-100 transition-all duration-300"
                    x-data="{ hover: false }"
                    @mouseenter="hover = true"
                    @mouseleave="hover = false"
                    :class="hover ? 'shadow-md border-indigo-200' : ''"
                >
                    <div class="flex items-start">
                        <div 
                            class="flex-shrink-0 bg-indigo-100 rounded-full p-2 transition-all duration-300"
                            :class="hover ? 'bg-indigo-200 rotate-12' : ''"
                        >
                            <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-indigo-800">Informations importantes</h3>
                            <div class="mt-3 text-indigo-700 space-y-3">
                                <div class="flex items-center" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                                    <div class="h-1.5 w-1.5 rounded-full bg-indigo-600 mr-2" :class="hover ? 'animate-ping' : ''"></div>
                                    <p :class="hover ? 'font-medium' : ''">Les modifications sont possibles uniquement si la date d'arrivée n'est pas passée</p>
                                </div>
                                <div class="flex items-center" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                                    <div class="h-1.5 w-1.5 rounded-full bg-indigo-600 mr-2" :class="hover ? 'animate-ping' : ''"></div>
                                    <p :class="hover ? 'font-medium' : ''">Des frais supplémentaires peuvent s'appliquer en cas de modification</p>
                                </div>
                                <div class="flex items-center" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                                    <div class="h-1.5 w-1.5 rounded-full bg-indigo-600 mr-2" :class="hover ? 'animate-ping' : ''"></div>
                                    <p :class="hover ? 'font-medium' : ''">La disponibilité de la chambre sera vérifiée aux nouvelles dates</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form x-data="{
                    dateDebut: '{{ $reservation->date_debut->format('Y-m-d') }}',
                    dateFin: '{{ $reservation->date_fin->format('Y-m-d') }}',
                    nombrePersonnes: {{ $reservation->nombre_personnes }},
                    capaciteMax: {{ $reservation->reservable->capacite }},
                    prixParNuit: {{ $reservation->reservable->prix }},
                    focused: {
                        dateDebut: false,
                        dateFin: false,
                        nombrePersonnes: false
                    },
                    calculerNuits() {
                        if (!this.dateDebut || !this.dateFin) return 0;
                        const start = new Date(this.dateDebut);
                        const end = new Date(this.dateFin);
                        const diffTime = Math.abs(end - start);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        return diffDays;
                    },
                    calculerPrixTotal() {
                        return this.calculerNuits() * this.prixParNuit;
                    },
                    animateValue(id, start, end, duration) {
                        const range = end - start;
                        const minTimer = 50;
                        let stepTime = Math.abs(Math.floor(duration / range));
                        stepTime = Math.max(stepTime, minTimer);
                        const startTime = new Date().getTime();
                        const endTime = startTime + duration;
                        let timer;
                        
                        const run = () => {
                            const now = new Date().getTime();
                            const remaining = Math.max((endTime - now) / duration, 0);
                            const value = Math.round(end - (remaining * range));
                            document.getElementById(id).textContent = value;
                            if (value === end) {
                                clearInterval(timer);
                            }
                        };
                        
                        timer = setInterval(run, stepTime);
                        run();
                    }
                }" 
                @submit="$event.target.classList.add('animate-pulse')"
                method="POST" 
                action="{{ route('touriste.reservations.hebergements.update', $reservation->id) }}"
                class="space-y-8"
                >
                    @csrf
                    @method('PUT')

                    <!-- Champs du formulaire -->
                    <div class="space-y-6">
                        <!-- Date d'arrivée -->
                        <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-300" :class="focused.dateDebut ? 'text-indigo-700 translate-x-1' : hover ? 'text-indigo-600' : ''">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transition-transform duration-300" :class="hover ? 'rotate-12' : ''" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    Date d'arrivée
                                </span>
                            </label>
                            
                            <div class="relative">
                                <input 
                                    type="date" 
                                    name="date_debut" 
                                    id="date_debut" 
                                    x-model="dateDebut" 
                                    min="{{ date('Y-m-d') }}"
                                    class="block w-full border rounded-lg shadow-sm py-3 px-4 focus:outline-none sm:text-sm transition-all duration-300"
                                    :class="focused.dateDebut ? 'border-indigo-500 ring-2 ring-indigo-200 bg-indigo-50' : hover ? 'border-indigo-300 bg-blue-50' : 'border-gray-300'"
                                    @focus="focused.dateDebut = true" 
                                    @blur="focused.dateDebut = false"
                                    @change="if(dateFin && dateDebut > dateFin) dateFin = dateDebut"
                                    required>

                                <div 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none transform transition-all duration-300"
                                    :class="focused.dateDebut ? 'opacity-100 scale-100' : 'opacity-0 scale-90'"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            
                            @error('date_debut')
                                <p class="mt-2 text-sm text-red-600 flex items-center animate-pulse">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Date de départ -->
                        <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-300" :class="focused.dateFin ? 'text-indigo-700 translate-x-1' : hover ? 'text-indigo-600' : ''">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transition-transform duration-300" :class="hover ? 'rotate-12' : ''" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    Date de départ
                                </span>
                            </label>
                            
                            <div class="relative">
                                <input 
                                    type="date" 
                                    name="date_fin" 
                                    id="date_fin" 
                                    x-model="dateFin" 
                                    :min="dateDebut"
                                    class="block w-full border rounded-lg shadow-sm py-3 px-4 focus:outline-none sm:text-sm transition-all duration-300"
                                    :class="focused.dateFin ? 'border-indigo-500 ring-2 ring-indigo-200 bg-indigo-50' : hover ? 'border-indigo-300 bg-blue-50' : 'border-gray-300'"
                                    @focus="focused.dateFin = true" 
                                    @blur="focused.dateFin = false"
                                    required>

                                <div 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none transform transition-all duration-300"
                                    :class="focused.dateFin ? 'opacity-100 scale-100' : 'opacity-0 scale-90'"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            
                            @error('date_fin')
                                <p class="mt-2 text-sm text-red-600 flex items-center animate-pulse">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Nombre de personnes -->
                        <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                            <label for="nombre_personnes" class="block text-sm font-medium text-gray-700 mb-1 transition-all duration-300" :class="focused.nombrePersonnes ? 'text-indigo-700 translate-x-1' : hover ? 'text-indigo-600' : ''">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transition-transform duration-300" :class="hover ? 'rotate-12' : ''" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                    </svg>
                                    Nombre de personnes
                                </span>
                            </label>
                            
                            <div class="relative">
                                <select 
                                    name="nombre_personnes" 
                                    id="nombre_personnes" 
                                    x-model="nombrePersonnes"
                                    class="block w-full border rounded-lg shadow-sm py-3 px-4 pr-10 focus:outline-none appearance-none sm:text-sm transition-all duration-300"
                                    :class="focused.nombrePersonnes ? 'border-indigo-500 ring-2 ring-indigo-200 bg-indigo-50' : hover ? 'border-indigo-300 bg-blue-50' : 'border-gray-300'"
                                    @focus="focused.nombrePersonnes = true" 
                                    @blur="focused.nombrePersonnes = false"
                                >
                                    <template x-for="i in capaciteMax" :key="i">
                                        <option :value="i" x-text="i"></option>
                                    </template>
                                </select>

                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 transition-colors duration-300" :class="focused.nombrePersonnes ? 'text-indigo-500' : hover ? 'text-indigo-400' : ''" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            
                            @error('nombre_personnes')
                                <p class="mt-2 text-sm text-red-600 flex items-center animate-pulse">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Récapitulatif de prix -->
                        <div 
                            class="bg-gradient-to-r from-blue-100 to-indigo-100 p-6 rounded-xl shadow-inner mt-8 transform transition-all duration-500"
                            x-data="{ hover: false }"
                            @mouseenter="hover = true"
                            @mouseleave="hover = false"
                            :class="hover ? 'scale-102 shadow-md' : ''"
                            x-init="$watch('dateFin', () => { 
                                if(calculerNuits() > 0) {
                                    animateValue('nights-count', 0, calculerNuits(), 800);
                                    animateValue('total-price', 0, calculerPrixTotal(), 1000);
                                }
                            })"
                        >
                            <h4 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                Récapitulatif de votre séjour
                            </h4>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-sm bg-white bg-opacity-60 p-3 rounded-lg">
                                    <span class="text-gray-700 font-medium">Prix par nuit</span>
                                    <span class="font-semibold text-indigo-700 bg-indigo-100 py-1 px-3 rounded-full" x-text="new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(prixParNuit)"></span>
                                </div>
                                
                                <div class="flex justify-between items-center text-sm bg-white bg-opacity-60 p-3 rounded-lg">
                                    <span class="text-gray-700 font-medium">Nombre de nuits</span>
                                    <span class="font-semibold text-indigo-700 bg-indigo-100 py-1 px-3 rounded-full">
                                        <span id="nights-count" x-text="calculerNuits()"></span>
                                    </span>
                                </div>
                                
                                <div class="border-t-2 border-indigo-200 border-dashed my-3"></div>
                                
                                <div class="flex justify-between items-center text-base font-bold  text-black p-4 rounded-lg shadow-sm">
                                    <span>Prix total</span>
                                    <span class="text-lg">
                                        <span id="total-price" x-text="calculerPrixTotal()"></span> €
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex justify-end space-x-4 pt-6">
                            <a 
                               
                                href="{{ route('touriste.reservations.hebergements.show', $reservation->id) }}" 
                                class="inline-flex justify-center items-center py-3 px-6 border border-indigo-300 shadow-sm text-sm font-medium rounded-md text-white  bg-yellow-600 hover:bg-stone-700  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300"
                                x-data="{ hover: false }"
                                @mouseenter="hover = true"
                                @mouseleave="hover = false"
                                :class="hover ? 'shadow-md -translate-y-0.5' : ''"
                            >
                                Annuler
                            </a>
                            
                            <button 
                                type="submit" 
                                class="inline-flex justify-center items-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300"
                                x-data="{ hover: false }"
                                @mouseenter="hover = true"
                                @mouseleave="hover = false"
                                :class="hover ? 'shadow-md -translate-y-0.5' : ''"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Confirmer les modifications
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Aide et suggestions -->
        <div class="mt-8">
            <div 
                class="bg-white shadow-lg rounded-xl overflow-hidden border border-indigo-100 transition-all duration-500"
                x-data="{ expanded: false, hover: false }"
                @mouseenter="hover = true"
                @mouseleave="hover = false"
                :class="hover ? 'shadow-indigo-200' : 'shadow-gray-200'"
            >
                <div 
                    class="bg-gradient-to-r bg-orange-500 px-6 py-4 flex justify-between items-center cursor-pointer"
                    @click="expanded = !expanded"
                >
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        Besoin d'aide pour votre réservation?
                    </h3>
                    <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        class="h-5 w-5 text-white transform transition-transform duration-300" 
                        :class="expanded ? 'rotate-180' : ''"
                        viewBox="0 0 20 20" 
                        fill="currentColor"
                    >
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                
                <div 
                    class="p-6 bg-gradient-to-br from-white to-indigo-50 transform origin-top transition-all duration-300 ease-in-out overflow-hidden"
                    :class="expanded ? 'max-h-96 opacity-100 scale-y-100' : 'max-h-0 opacity-0 scale-y-0'"
                >
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-indigo-100 rounded-full p-2">
                                <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-md font-semibold text-indigo-800">Contactez-nous</h4>
                                <p class="mt-1 text-sm text-gray-600">Notre équipe est disponible 24/7 pour vous assister avec votre réservation.</p>
                                <a href="#" class="mt-2 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    Appeler le service client
                                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-indigo-100 rounded-full p-2">
                                <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-md font-semibold text-indigo-800">FAQ</h4>
                                <p class="mt-1 text-sm text-gray-600">Consultez notre FAQ pour des réponses aux questions les plus fréquentes.</p>
                                <a href="#" class="mt-2 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    Voir la FAQ
                                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
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
@endsection