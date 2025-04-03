@extends('layouts.guide')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="reservationsApp()">
    <!-- Messages d'alerte -->
    <div class="mb-6">
        <!-- Message de succès -->
        <div x-show="successMessage" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded flex items-center justify-between mb-3">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-500"></i>
                <span x-text="successMessage"></span>
            </div>
            <button @click="successMessage = ''" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Message d'erreur -->
        <div x-show="errorMessage" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded flex items-center justify-between mb-3">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                <span x-text="errorMessage"></span>
            </div>
            <button @click="errorMessage = ''" class="text-red-700 hover:text-red-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
    <!-- Titre et compteur -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-indigo-800 relative">
            <span class="relative z-10">Mes Réservations</span>
            <span class="absolute -bottom-2 left-0 w-1/3 h-3 bg-yellow-300 opacity-50 z-0"></span>
        </h1>
        <div class="mt-4 md:mt-0">
            <span class="text-gray-500" x-text="`${filteredReservations.length} réservation(s) trouvée(s)`"></span>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow-lg rounded-xl p-6 mb-8 transition-all duration-300 hover:shadow-xl border-l-4 border-indigo-500"
         :class="{'border-indigo-600': isFilterActive}" x-data="{ isOpen: true }">
        <div class="flex justify-between items-center mb-4 cursor-pointer" @click="isOpen = !isOpen">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <h2 class="text-lg font-semibold text-gray-700">Filtres de recherche</h2>
            </div>
            <button class="focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 transition-transform duration-300" :class="{'transform rotate-180': !isOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
        
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform -translate-y-4" 
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-4">
            <form method="GET" action="{{ route('guide.reservations') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Statut</label>
                    <select name="statut" x-model="filters.statut" @change="applyFilters()" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente">En Attente</option>
                        <option value="approuvé">Approuvé</option>
                        <option value="rejeté">Rejeté</option>
                        <option value="annulé">Annulé</option>
                        <option value="terminé">Terminé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Date début</label>
                    <input type="date" name="date_debut" x-model="filters.dateDebut" @change="applyFilters()" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Date fin</label>
                    <input type="date" name="date_fin" x-model="filters.dateFin" @change="applyFilters()" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                </div>
                <div class="flex items-end">
                    <button type="button" @click="resetFilters()" 
                            class="w-full md:w-auto px-4 py-2 mr-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200">
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Réinitialiser
                        </div>
                    </button>
                    <button type="submit" 
                            class="w-full md:w-auto px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Filtrer
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Réservations -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden" x-show="!isLoading">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('user')">
                            <div class="flex items-center">
                                <span>Touriste</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 text-gray-400 group-hover:text-gray-500" :class="{'text-indigo-500': sortColumn === 'user'}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('site')">
                            <div class="flex items-center">
                                <span>Site/Circuit</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 text-gray-400 group-hover:text-gray-500" :class="{'text-indigo-500': sortColumn === 'site'}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('date')">
                            <div class="flex items-center">
                                <span>Dates</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 text-gray-400 group-hover:text-gray-500" :class="{'text-indigo-500': sortColumn === 'date'}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer group" @click="sortBy('statut')">
                            <div class="flex items-center">
                                <span>Statut</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 text-gray-400 group-hover:text-gray-500" :class="{'text-indigo-500': sortColumn === 'statut'}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="(reservation, index) in paginatedReservations" :key="reservation.id">
                        <tr class="hover:bg-gray-50 transition-colors duration-150"
                            x-show="true"
                            x-transition:enter="transition-opacity ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-800 font-bold uppercase">
                                        <span x-text="getInitials(reservation.user.nom, reservation.user.prenom)"></span>
                                    </div>
                                    <div class="ml-4">
                                        <a :href="`${routeUserShow.replace(':id', reservation.user.id)}`" class="text-sm font-medium text-gray-900 hover:text-indigo-600 transition-colors duration-150">
                                            <span x-text="reservation.user.prenom"></span>
                                            <span x-text="reservation.user.nom"></span>
                                        </a>
                                        <div class="text-sm text-gray-500" x-text="reservation.user.email || ''"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900" x-text="reservation.reservable.nom || 'Non spécifié'"></div>
                                <div class="text-sm text-gray-500" x-text="reservation.reservable.type || ''"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="formatDate(reservation.date_debut)"></span>
                                    <span class="mx-1">-</span>
                                    <span x-text="formatDate(reservation.date_fin)"></span>
                                </div>
                                <div class="text-xs text-gray-500" x-text="`${getDurationDays(reservation.date_debut, reservation.date_fin)} jours`"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-medium rounded-full"
                                      :class="{
                                          'bg-yellow-100 text-yellow-800': reservation.statut === 'en_attente',
                                          'bg-green-100 text-green-800': reservation.statut === 'approuvé',
                                          'bg-red-100 text-red-800': reservation.statut === 'rejeté',
                                          'bg-gray-100 text-gray-800': reservation.statut === 'annulé',
                                          'bg-blue-100 text-blue-800': reservation.statut === 'terminé'
                                      }"
                                      x-text="formatStatut(reservation.statut)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center space-x-2">
                                    <a :href="`${routeReservationShow.replace(':id', reservation.id)}`"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Détails
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredReservations.length === 0">
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-lg font-medium">Aucune réservation trouvée</span>
                                <p class="text-sm mt-1" x-show="isFilterActive">Essayez de modifier vos filtres de recherche</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- État de chargement -->
    <div class="flex justify-center items-center py-12" x-show="isLoading">
        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="ml-3 text-lg font-medium text-gray-700">Chargement des réservations...</span>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex items-center justify-between bg-white px-4 py-3 sm:px-6 shadow-md rounded-lg" x-show="totalPages > 0">
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Affichage de
                    <span class="font-medium" x-text="currentPage * itemsPerPage - itemsPerPage + 1"></span>
                    à
                    <span class="font-medium" x-text="Math.min(currentPage * itemsPerPage, filteredReservations.length)"></span>
                    sur
                    <span class="font-medium" x-text="filteredReservations.length"></span>
                    résultats
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button @click="previousPage()" :disabled="currentPage === 1" 
                            :class="{'cursor-not-allowed opacity-50': currentPage === 1}"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Précédent</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <template x-for="page in pagesArray">
                        <button @click="goToPage(page)" 
                                :class="{'bg-indigo-50 border-indigo-500 text-indigo-600': page === currentPage, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': page !== currentPage}"
                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                            <span x-text="page"></span>
                        </button>
                    </template>
                    <button @click="nextPage()" :disabled="currentPage === totalPages" 
                            :class="{'cursor-not-allowed opacity-50': currentPage === totalPages}"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Suivant</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</div>


<script>
    function reservationsApp() {
        return {
            reservations: @json($reservations->items()),
            filteredReservations: [],
            paginatedReservations: [],
            currentPage: 1,
            itemsPerPage: 10,
            totalPages: 1,
            isLoading: true,
            sortColumn: 'date',
            sortDirection: 'desc',
            filters: {
                statut: '{{ request('statut') }}',
                dateDebut: '{{ request('date_debut') }}',
                dateFin: '{{ request('date_fin') }}'
            },
            routeUserShow: '{{ route("users.show", ":id") }}',
            routeReservationShow: '{{ route("guide.reservations.show", ":id") }}',
            successMessage: '',
            errorMessage: '',

            
            init() {
                this.isLoading = true;
                setTimeout(() => {
                    this.applyFilters();
                    this.isLoading = false;
                    this.checkSessionMessages();
                    this.loadReservations();
                }, 300);
               
            },
            
            checkSessionMessages() {
                // Pour Laravel avec Alpine.js, vous pouvez utiliser des données injectées
                // ou récupérer les messages via des attributs data ou des variables JavaScript
                
                // Exemple avec des variables injectées par Blade
                if (typeof sessionSuccess !== 'undefined' && sessionSuccess) {
                    this.successMessage = sessionSuccess;
                    // Optionnel: Effacer le message après quelques secondes
                    setTimeout(() => {
                        this.successMessage = '';
                    }, 5000);
                }
                
                if (typeof sessionError !== 'undefined' && sessionError) {
                    this.errorMessage = sessionError;
                    setTimeout(() => {
                        this.errorMessage = '';
                    }, 5000);
                }
            },

            applyFilters() {
                this.filteredReservations = this.reservations.filter(reservation => {
                    let matchStatut = true;
                    let matchDateDebut = true;
                    let matchDateFin = true;
                    
                    if (this.filters.statut) {
                        matchStatut = reservation.statut === this.filters.statut;
                    }
                    
                    if (this.filters.dateDebut) {
                        matchDateDebut = new Date(reservation.date_debut) >= new Date(this.filters.dateDebut);
                    }
                    
                    if (this.filters.dateFin) {
                        matchDateFin = new Date(reservation.date_fin) <= new Date(this.filters.dateFin);
                    }
                    
                    return matchStatut && matchDateDebut && matchDateFin;
                });
                
                this.sortData();
                this.calculatePagination();
                this.goToPage(1);
            },
            
            get isFilterActive() {
                return this.filters.statut || this.filters.dateDebut || this.filters.dateFin;
            },
            
            resetFilters() {
                this.filters = {
                    statut: '',
                    dateDebut: '',
                    dateFin: ''
                };
                this.applyFilters();
            },
            
            sortBy(column) {
                if (this.sortColumn === column) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortColumn = column;
                    this.sortDirection = 'asc';
                }
                
                this.sortData();
                this.updatePaginatedData();
            },
            
            sortData() {
                const column = this.sortColumn;
                
                this.filteredReservations.sort((a, b) => {
                    let valueA, valueB;
                    
                    if (column === 'user') {
                        valueA = a.user.nom + a.user.prenom;
                        valueB = b.user.nom + b.user.prenom;
                    } else if (column === 'site') {
                        valueA = a.reservable.nom || '';
                        valueB = b.reservable.nom || '';
                    } else if (column === 'date') {
                        valueA = new Date(a.date_debut);
                        valueB = new Date(b.date_debut);
                    } else if (column === 'statut') {
                        valueA = a.statut;
                        valueB = b.statut;
                    } else {
                        valueA = a[column];
                        valueB = b[column];
                    }
                    
                    if (this.sortDirection === 'asc') {
                        return valueA > valueB ? 1 : -1;
                    } else {
                        return valueA < valueB ? 1 : -1;
                    }
                });
            },
            
            calculatePagination() {
                this.totalPages = Math.ceil(this.filteredReservations.length / this.itemsPerPage);
            },
            
            get pagesArray() {
                const pages = [];
                const maxPagesToShow = 5;
                
                if (this.totalPages <= maxPagesToShow) {
                    for (let i = 1; i <= this.totalPages; i++) {
                        pages.push(i);
                    }
                } else {
                    const halfWay = Math.ceil(maxPagesToShow / 2);
                    
                    if (this.currentPage <= halfWay) {
                        for (let i = 1; i <= maxPagesToShow; i++) {
                            pages.push(i);
                        }
                    } else if (this.currentPage > this.totalPages - halfWay) {
                        for (let i = this.totalPages - maxPagesToShow + 1; i <= this.totalPages; i++) {
                            pages.push(i);
                        }
                    } else {
                        for (let i = this.currentPage - halfWay + 1; i <= this.currentPage + halfWay - 1; i++) {
                            pages.push(i);
                        }
                    }
                }
                
                return pages;
            },
            
            updatePaginatedData() {
                const startIndex = (this.currentPage - 1) * this.itemsPerPage;
                const endIndex = Math.min(startIndex + this.itemsPerPage, this.filteredReservations.length);
                this.paginatedReservations = this.filteredReservations.slice(startIndex, endIndex);
            },
            
            goToPage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                    this.updatePaginatedData();
                }
            },
            
            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.goToPage(this.currentPage + 1);
                }
            },
            
            previousPage() {
                if (this.currentPage > 1) {
                    this.goToPage(this.currentPage - 1);
                }
            },
            
            formatDate(date) {
                return new Date(date).toLocaleDateString('fr-FR');
            },
            
            getDurationDays(startDate, endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const diffTime = Math.abs(end - start);
                return Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            },
            
            formatStatut(statut) {
                const formats = {
                    'en_attente': 'En attente',
                    'approuvé': 'Approuvé',
                    'rejeté': 'Rejeté',
                    'annulé': 'Annulé',
                    'terminé': 'Terminé'
                };
                
                return formats[statut] || statut;
            },
            
            getInitials(nom, prenom) {
                if (!nom && !prenom) return '??';
                return (nom?.charAt(0) || '') + (prenom?.charAt(0) || '');
            }
        };
    }
</script>

@endsection