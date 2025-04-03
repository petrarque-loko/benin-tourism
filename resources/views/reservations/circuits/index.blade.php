@extends('layouts.app')

@section('title', 'Mes réservations de circuits')

@section('content')
<div x-data="reservationsApp()" class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-3xl font-extrabold text-gray-800 bg-gradient-to-r from-blue-600 to-indigo-800 bg-clip-text text-transparent">
            Mes réservations de circuits
        </h1>
        <a href="{{ route('circuits.index') }}" class="group relative bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-medium py-2.5 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden">
            <span class="relative z-10 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transform group-hover:rotate-180 transition-transform duration-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nouvelle réservation
            </span>
            <div class="absolute inset-0 w-0 bg-white bg-opacity-30 group-hover:w-full transition-all duration-300 ease-out"></div>
        </a>
    </div>

    @if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        x-init="setTimeout(() => show = false, 5000)" 
        class="bg-gradient-to-r from-green-50 to-teal-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" 
        role="alert"
    >
        <div class="flex items-center">
            <div class="py-1">
                <svg class="h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
            <div class="ml-auto">
                <button @click="show = false" class="text-green-700 hover:text-green-900 transition duration-200">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($reservations->isEmpty())
    <div 
        x-data="{}" 
        x-init="$nextTick(() => { anime.timeline().add({ targets: '#empty-state-icon', opacity: [0, 1], translateY: [30, 0], easing: 'easeOutExpo', duration: 1000 }).add({ targets: '#empty-state-text', opacity: [0, 1], translateY: [20, 0], easing: 'easeOutExpo', duration: 800, delay: 200 }).add({ targets: '#empty-state-action', opacity: [0, 1], translateY: [10, 0], easing: 'easeOutExpo', duration: 600, delay: 100 }); })"
        class="bg-white border border-gray-200 rounded-xl p-12 text-center shadow-sm"
    >
        <img id="empty-state-icon" src="{{ asset('images/empty-reservations.svg') }}" alt="Aucune réservation" class="h-48 mx-auto mb-6 opacity-0">
        <div id="empty-state-text" class="opacity-0">
            <h2 class="text-2xl font-bold text-gray-800 mb-3">Vous n'avez pas encore de réservations</h2>
            <p class="text-lg text-gray-600 mb-8">Commencez par réserver un circuit pour découvrir les merveilles touristiques disponibles.</p>
        </div>
        <a id="empty-state-action" href="{{ route('touriste.reservations.circuits.create') }}" class="opacity-0 inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-medium py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Réserver un circuit
        </a>
    </div>
    @else
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        <!-- Filtres et recherche -->
        <div 
            x-data="{ openFilters: false }" 
            class="border-b border-gray-200 p-5 bg-gradient-to-r from-gray-50 to-gray-100"
        >
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="relative flex-grow">
                    <input 
                        type="text" 
                        placeholder="Rechercher par nom de circuit ou guide..." 
                        x-model="searchTerm" 
                        @input="filterReservations()" 
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"
                    >
                    <div class="absolute left-4 top-3.5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <button 
                    @click="openFilters = !openFilters" 
                    class="flex items-center text-gray-700 hover:text-blue-600 py-2 px-4 border border-gray-300 hover:border-blue-500 rounded-lg transition duration-200 bg-white shadow-sm"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Filtres</span>
                    <svg 
                        x-bind:class="openFilters ? 'rotate-180' : ''" 
                        class="h-4 w-4 ml-2 transform transition-transform duration-300" 
                        xmlns="http://www.w3.org/2000/svg" 
                        fill="none" viewBox="0 0 24 24" 
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            
            <div 
                x-show="openFilters" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-5"
            >
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select 
                        x-model="statusFilter" 
                        @change="filterReservations()"
                        class="w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-4 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="en_attente">En attente</option>
                        <option value="approuvé">Approuvé</option>
                        <option value="annulee">Annulée</option>
                        <option value="terminee">Terminée</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input 
                        type="date" 
                        x-model="startDateFilter" 
                        @change="filterReservations()"
                        class="w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-4 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input 
                        type="date" 
                        x-model="endDateFilter" 
                        @change="filterReservations()"
                        class="w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-4 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                    >
                </div>
                
                <div class="md:col-span-3 flex justify-end mt-2">
                    <button 
                        @click="resetFilters()" 
                        class="text-blue-600 hover:text-blue-800 font-medium transition duration-200 flex items-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Réinitialiser les filtres
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Liste des réservations -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <button @click="sortBy('nom')" class="group flex items-center space-x-1 focus:outline-none">
                                <span>Circuit</span>
                                <span class="transform group-hover:text-blue-500 transition-colors duration-200" :class="{'text-blue-600': sortColumn === 'nom'}">
                                    <svg x-show="sortColumn === 'nom' && sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                    <svg x-show="sortColumn === 'nom' && sortDirection === 'desc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <svg x-show="sortColumn !== 'nom'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </span>
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <button @click="sortBy('date_debut')" class="group flex items-center space-x-1 focus:outline-none">
                                <span>Dates</span>
                                <span class="transform group-hover:text-blue-500 transition-colors duration-200" :class="{'text-blue-600': sortColumn === 'date_debut'}">
                                    <svg x-show="sortColumn === 'date_debut' && sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                    <svg x-show="sortColumn === 'date_debut' && sortDirection === 'desc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <svg x-show="sortColumn !== 'date_debut'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </span>
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <button @click="sortBy('nombre_personnes')" class="group flex items-center space-x-1 focus:outline-none">
                                <span>Personnes</span>
                                <span class="transform group-hover:text-blue-500 transition-colors duration-200" :class="{'text-blue-600': sortColumn === 'nombre_personnes'}">
                                    <svg x-show="sortColumn === 'nombre_personnes' && sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                    <svg x-show="sortColumn === 'nombre_personnes' && sortDirection === 'desc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <svg x-show="sortColumn !== 'nombre_personnes'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </span>
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <button @click="sortBy('statut')" class="group flex items-center space-x-1 focus:outline-none">
                                <span>Statut</span>
                                <span class="transform group-hover:text-blue-500 transition-colors duration-200" :class="{'text-blue-600': sortColumn === 'statut'}">
                                    <svg x-show="sortColumn === 'statut' && sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                    <svg x-show="sortColumn === 'statut' && sortDirection === 'desc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <svg x-show="sortColumn !== 'statut'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </span>
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <button @click="sortBy('statut_paiement')" class="group flex items-center space-x-1 focus:outline-none">
                                <span>Paiement</span>
                                <span class="transform group-hover:text-blue-500 transition-colors duration-200" :class="{'text-blue-600': sortColumn === 'statut_paiement'}">
                                    <svg x-show="sortColumn === 'statut_paiement' && sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                    <svg x-show="sortColumn === 'statut_paiement' && sortDirection === 'desc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <svg x-show="sortColumn !== 'statut_paiement'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </span>
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-if="filteredReservations.length === 0">
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-lg font-medium">Aucune réservation ne correspond à votre recherche</p>
                                    <button @click="resetFilters()" class="mt-4 text-blue-600 hover:text-blue-800 font-medium">Réinitialiser les filtres</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-for="(reservation, index) in filteredReservations" :key="reservation.id">
                        <tr 
                            class="hover:bg-blue-50 transition-all duration-200" 
                            x-show="!reservation.deleted"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-4"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform translate-y-4"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-start">
                                    <div class="h-10 w-10 flex-shrink-0 mr-3">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                                            <span x-text="reservation.reservable.nom.charAt(0)"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900" x-text="reservation.reservable.nom"></div>
                                        <div class="text-sm text-gray-500 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span x-text="`Guide: ${reservation.guide.prenom} ${reservation.guide.nom}`"></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="formatDate(reservation.date_debut)"></span>
                                </div>
                                <div class="text-sm text-gray-500 flex items-center mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="`au ${formatDate(reservation.date_fin)}`"></span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="`Durée: ${reservation.reservable.duree} jours`"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span x-text="reservation.nombre_personnes"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    x-text="getStatusText(reservation.statut)" 
                                    :class="{
                                        'bg-yellow-100 text-yellow-800': reservation.statut === 'en_attente',
                                        'bg-green-100 text-green-800': reservation.statut === 'approuvé',
                                        'bg-red-100 text-red-800': reservation.statut === 'annulee',
                                        'bg-blue-100 text-blue-800': reservation.statut === 'terminee'
                                    }"
                                    class="px-3 py-1.5 rounded-full text-xs font-medium"
                                ></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    x-text="getPaymentStatusText(reservation.statut_paiement)" 
                                    :class="{
                                        'bg-yellow-100 text-yellow-800': reservation.statut_paiement === 'en_attente',
                                        'bg-green-100 text-green-800': reservation.statut_paiement === 'payé',
                                        'bg-red-100 text-red-800': reservation.statut_paiement === 'remboursé'
                                    }"
                                    class="px-3 py-1.5 rounded-full text-xs font-medium"
                                ></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" class="bg-gray-100 rounded-md p-2 hover:bg-gray-200 focus:outline-none transition duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                        </svg>
                                    </button>
                                    <div
                                        x-show="open"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
                                    >
                                        <div class="py-1">
                                            <a :href="`{{ route('touriste.reservations.circuits.show', '') }}/${reservation.id}`" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Voir détails
                                            </a>
                                            <template x-if="reservation.statut === 'en_attente'">
                                                <a x-data="{ url: '{{ route('touriste.reservations.circuits.edit', ['id' => '__ID__']) }}' }"
                                                    x-bind:href="url.replace('__ID__', reservation.id)"
                                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                    Modifie
                                                </a>
                                            </template>
                                            <template x-if="['en_attente', 'approuvé'].includes(reservation.statut)">
                                                <a x-data="{ url: '{{ route('touriste.reservations.circuits.confirm-cancel', ['id' => '__ID__']) }}' }"
                                                    x-bind:href="url.replace('__ID__', reservation.id)"
                                                    class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Annuler
                                                </a>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-white border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Précédent
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Suivant
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de <span class="font-medium" x-text="filteredReservations.length"></span> résultats
                        </p>
                    </div>
                    <div>
                        <!-- La pagination sera gérée avec Alpine.js -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function reservationsApp() {
    return {
        reservations: @json($reservations->items()),
        filteredReservations: [],
        searchTerm: '',
        statusFilter: '',
        startDateFilter: '',
        endDateFilter: '',
        sortColumn: 'date_debut',
        sortDirection: 'desc',
        
        init() {
            this.filterReservations();
            
            // Ajouter les propriétés supplémentaires aux réservations
            this.reservations.forEach(reservation => {
                reservation.deleted = false;
            });
        },
        
        filterReservations() {
            this.filteredReservations = this.reservations.filter(reservation => {
                // Filtrage par recherche
                const searchMatch = this.searchTerm === '' || 
                    reservation.reservable.nom.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                    (reservation.guide && `${reservation.guide.prenom} ${reservation.guide.nom}`.toLowerCase().includes(this.searchTerm.toLowerCase()));
                
                // Filtrage par statut
                const statusMatch = this.statusFilter === '' || reservation.statut === this.statusFilter;
                
                // Filtrage par date de début
                const startDateMatch = this.startDateFilter === '' || new Date(reservation.date_debut) >= new Date(this.startDateFilter);
                
                // Filtrage par date de fin
                const endDateMatch = this.endDateFilter === '' || new Date(reservation.date_fin) <= new Date(this.endDateFilter);
                
                return searchMatch && statusMatch && startDateMatch && endDateMatch;
            });
            
            // Tri des résultats
            this.sortReservations();
        },
        
        sortBy(column) {
            if (this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }
            
            this.sortReservations();
        },
        
        sortReservations() {
            this.filteredReservations.sort((a, b) => {
                let valueA, valueB;
                
                switch (this.sortColumn) {
                    case 'nom':
                        valueA = a.reservable.nom.toLowerCase();
                        valueB = b.reservable.nom.toLowerCase();
                        break;
                    case 'date_debut':
                        valueA = new Date(a.date_debut);
                        valueB = new Date(b.date_debut);
                        break;
                    case 'nombre_personnes':
                        valueA = a.nombre_personnes;
                        valueB = b.nombre_personnes;
                        break;
                    case 'statut':
                        valueA = a.statut;
                        valueB = b.statut;
                        break;
                    case 'statut_paiement':
                        valueA = a.statut_paiement;
                        valueB = b.statut_paiement;
                        break;
                    default:
                        valueA = a[this.sortColumn];
                        valueB = b[this.sortColumn];
                }
                
                if (valueA < valueB) return this.sortDirection === 'asc' ? -1 : 1;
                if (valueA > valueB) return this.sortDirection === 'asc' ? 1 : -1;
                return 0;
            });
        },
        
        resetFilters() {
            this.searchTerm = '';
            this.statusFilter = '';
            this.startDateFilter = '';
            this.endDateFilter = '';
            this.filterReservations();
        },
        
        formatDate(dateString) {
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('fr-FR', options);
        },
        
        getStatusText(status) {
            switch (status) {
                case 'en_attente': return 'En attente';
                case 'approuvé': return 'Approuvé';
                case 'annulee': return 'Annulée';
                case 'terminee': return 'Terminée';
                default: return status;
            }
        },
        
        getPaymentStatusText(status) {
            switch (status) {
                case 'en_attente': return 'En attente';
                case 'payé': return 'Payé';
                case 'remboursé': return 'Remboursé';
                default: return status;
            }
        }
    };
}
</script>
@endsection