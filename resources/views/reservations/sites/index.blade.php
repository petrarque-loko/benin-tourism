@extends('layouts.app')

@section('content')
    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
        
    </style>

<div class="h-full font-sans antialiased ">
    <div x-data="reservations()" class="min-h-full  from-blue-50 to-indigo-50">
        <header class=" shadow">
            <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 flex items-center">
                    <i class="fas fa-calendar-check text-indigo-600 mr-3"></i>
                    Mes Réservations
                </h1>
                <div class="flex space-x-3">
                    <button @click="viewMode = 'grid'" :class="{'bg-indigo-100 text-indigo-700': viewMode === 'grid'}" class="px-3 py-2 rounded-md flex items-center text-gray-700 hover:bg-indigo-50 transition-all">
                        <i class="fas fa-th-large mr-2"></i> Grille
                    </button>
                    <button @click="viewMode = 'list'" :class="{'bg-indigo-100 text-indigo-700': viewMode === 'list'}" class="px-3 py-2 rounded-md flex items-center text-gray-700 hover:bg-indigo-50 transition-all">
                        <i class="fas fa-list mr-2"></i> Liste
                    </button>
                </div>
            </div>
        </header>
        
        <main class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Notification -->
            <div x-show="showNotification" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="mb-6 p-4 border-l-4 border-green-500 bg-green-50 rounded-md shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800" x-text="notificationMessage"></p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button @click="showNotification = false" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <span class="sr-only">Fermer</span>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filtres -->
            <div class="mb-6 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-grow md:flex-grow-0">
                        <label for="filter" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par statut</label>
                        <select id="filter" x-model="filter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="all">Tous les statuts</option>
                            <option value="en_attente">En attente</option>
                            <option value="approuvé">Approuvé</option>
                            <option value="rejeté">Rejeté</option>
                            <option value="annulé">Annulé</option>
                            <option value="terminé">Terminé</option>
                        </select>
                    </div>
                    
                    <div class="flex-grow md:flex-grow-0">
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Trier par</label>
                        <select id="sort" x-model="sortBy" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="date">Date (plus récente)</option>
                            <option value="date_asc">Date (plus ancienne)</option>
                            <option value="site">Site/Activité (A-Z)</option>
                            <option value="statut">Statut</option>
                        </select>
                    </div>
                    
                    <div class="flex-grow md:flex-grow-0">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="search" x-model="searchQuery" placeholder="Rechercher..." class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Message si aucune réservation -->
            <div x-show="filteredReservations.length === 0" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center">
                <div class="text-indigo-500 mb-4">
                    <i class="fas fa-calendar-xmark text-5xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Aucune réservation trouvée</h3>
                <p class="text-gray-500" x-show="filter !== 'all' || searchQuery">Essayez de modifier vos filtres de recherche</p>
                <p class="text-gray-500" x-show="filter === 'all' && !searchQuery">Vous n'avez pas encore de réservations</p>
                <div class="mt-6">
                    <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i> Faire une réservation
                    </a>
                </div>
            </div>

            <!-- Vue Grille -->
            <div x-show="viewMode === 'grid' && filteredReservations.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="reservation in paginatedReservations" :key="reservation.id">
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden transition-all hover:shadow-lg transform hover:-translate-y-1"
                         :class="reservation.statut === 'en_attente' ? 'border-l-4 border-l-yellow-500' : 
                                reservation.statut === 'approuvé' ? 'border-l-4 border-l-green-500' : 
                                reservation.statut === 'rejeté' ? 'border-l-4 border-l-red-500' : 
                                reservation.statut === 'annulé' ? 'border-l-4 border-l-gray-500' : 
                                'border-l-4 border-l-blue-500'">
                        <div class="px-6 py-4 border-b border-gray-200 bg-blue-100">
                            <div class="flex justify-between items-start ">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1" x-text="reservation.reservable ? reservation.reservable.nom : 'Site supprimé'"></h3>
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-calendar mr-2 text-indigo-500"></i>
                                        <span x-text="formatDate(reservation.date_debut) + ' - ' + formatDate(reservation.date_fin)"></span>
                                    </p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                      :class="reservation.statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 
                                              reservation.statut === 'approuvé' ? 'bg-green-100 text-green-800' : 
                                              reservation.statut === 'rejeté' ? 'bg-red-100 text-red-800' : 
                                              reservation.statut === 'annulé' ? 'bg-gray-100 text-gray-800' : 
                                              'bg-blue-100 text-blue-800'">
                                    <i class="mr-1"
                                       :class="reservation.statut === 'en_attente' ? 'fas fa-clock' : 
                                               reservation.statut === 'approuvé' ? 'fas fa-check' : 
                                               reservation.statut === 'rejeté' ? 'fas fa-times' : 
                                               reservation.statut === 'annulé' ? 'fas fa-ban' : 
                                               'fas fa-check-double'"></i>
                                    <span x-text="reservation.statut"></span>
                                </span>
                            </div>
                        </div>
                        <div class="px-6 py-4">
                            <div class="flex items-center mb-3">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Guide</div>
                                    <div class="text-sm text-gray-500" x-text="reservation.guide ? reservation.guide.prenom + ' ' + reservation.guide.nom : 'Aucun guide'"></div>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-3 bg-gray-50 flex justify-between items-center">
                            <a :href="'sites/' + reservation.id" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                                <i class="fas fa-eye mr-1"></i> Détails
                            </a>
                            <div class="flex space-x-2" x-show="reservation.statut === 'en_attente'">
                                <a :href="'sites/' + reservation.id + '/edit'" class="text-sm font-medium text-blue-600 hover:text-blue-500 flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Modifier
                                </a>
                                <button @click="confirmCancel(reservation.id)" class="text-sm font-medium text-red-600 hover:text-red-500 flex items-center">
                                    <i class="fas fa-times mr-1"></i> Annuler
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Vue Liste -->
            <div x-show="viewMode === 'list' && filteredReservations.length > 0" class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center cursor-pointer" @click="toggleSort('date')">
                                        Date
                                        <i class="fas fa-sort ml-1"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center cursor-pointer" @click="toggleSort('site')">
                                        Site/Activité
                                        <i class="fas fa-sort ml-1"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Guide
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center cursor-pointer" @click="toggleSort('statut')">
                                        Statut
                                        <i class="fas fa-sort ml-1"></i>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="reservation in paginatedReservations" :key="reservation.id">
                                <tr class="hover:bg-gray-50 transition-colors duration-200"
                                    :class="reservation.statut === 'en_attente' ? 'bg-yellow-50' : 
                                            reservation.statut === 'approuvé' ? 'bg-green-50' : 
                                            reservation.statut === 'rejeté' ? 'bg-red-50' : 
                                            reservation.statut === 'annulé' ? 'bg-gray-50' : 
                                            'bg-blue-50'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900" x-text="formatDate(reservation.date_debut) + ' - ' + formatDate(reservation.date_fin)"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900" x-text="reservation.reservable ? reservation.reservable.nom : 'Site supprimé'"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm text-gray-900" x-text="reservation.guide ? reservation.guide.prenom + ' ' + reservation.guide.nom : 'Aucun guide'"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                              :class="reservation.statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 
                                                      reservation.statut === 'approuvé' ? 'bg-green-100 text-green-800' : 
                                                      reservation.statut === 'rejeté' ? 'bg-red-100 text-red-800' : 
                                                      reservation.statut === 'annulé' ? 'bg-gray-100 text-gray-800' : 
                                                      'bg-blue-100 text-blue-800'">
                                            <i class="mr-1"
                                               :class="reservation.statut === 'en_attente' ? 'fas fa-clock' : 
                                                       reservation.statut === 'approuvé' ? 'fas fa-check' : 
                                                       reservation.statut === 'rejeté' ? 'fas fa-times' : 
                                                       reservation.statut === 'annulé' ? 'fas fa-ban' : 
                                                       'fas fa-check-double'"></i>
                                            <span x-text="reservation.statut"></span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a :href="'sites/' + reservation.id" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <i class="fas fa-eye"></i> Détails
                                        </a>
                                        <template x-if="reservation.statut === 'en_attente'">
                                            <div class="inline-flex space-x-2">
                                                <a :href="'sites/' + reservation.id + '/edit'" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <button @click="confirmCancel(reservation.id)" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-times"></i> Annuler
                                                </button>
                                            </div>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6" x-show="totalPages > 1">
                <div class="flex justify-between items-center bg-white px-4 py-3 sm:px-6 border rounded-md shadow-sm">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button @click="prevPage" :disabled="currentPage === 1" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Précédent
                        </button>
                        <button @click="nextPage" :disabled="currentPage === totalPages" :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Suivant
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Affichage de <span class="font-medium" x-text="(currentPage - 1) * perPage + 1"></span> à
                                <span class="font-medium" x-text="Math.min(currentPage * perPage, filteredReservations.length)"></span> sur
                                <span class="font-medium" x-text="filteredReservations.length"></span> réservations
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <button @click="prevPage" :disabled="currentPage === 1" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Précédent</span>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <template x-for="page in paginationItems" :key="page.label">
                                    <template x-if="page === '...'">
                                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>
                                    </template>
                                    <template x-if="page !== '...'">
                                        <button @click="goToPage(page)" :class="{'bg-indigo-100 border-indigo-500 text-indigo-600': currentPage === page, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== page}" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium" x-text="page"></button>
                                    </template>
                                </template>
                                <button @click="nextPage" :disabled="currentPage === totalPages" :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Suivant</span>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <!-- Modal de confirmation d'annulation -->
        <div x-show="showCancelModal" class="fixed z-10 inset-0 overflow-y-auto" x-cloak>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCancelModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity" 
                     aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showCancelModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Annuler la réservation
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Êtes-vous sûr de vouloir annuler cette réservation? Cette action est irréversible.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form :action="'sites/' + reservationToCancel + '/cancel'" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" :value="csrfToken">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Confirmer
                            </button>
                        </form>
                        <button @click="cancelModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        function reservations() {
            return {
                reservations: [],
                filter: 'all',
                sortBy: 'date',
                sortOrder: 'desc',
                searchQuery: '',
                currentPage: 1,
                perPage: 10,
                viewMode: 'grid',
                showCancelModal: false,
                reservationToCancel: null,
                showNotification: false,
                notificationMessage: '',
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                
                init() {
                    // Récupérer les données de réservations depuis Laravel
                    this.reservations = JSON.parse(document.getElementById('reservations-data').textContent);
                    
                    // Vérifier si un message de succès est présent dans l'URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const successMessage = urlParams.get('success');
                    if (successMessage) {
                        this.showNotification = true;
                        this.notificationMessage = decodeURIComponent(successMessage);
                        // Supprimer le paramètre de l'URL
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }
                    
                    // Récupérer les préférences de l'utilisateur depuis le localStorage
                    this.loadUserPreferences();
                },

                loadUserPreferences() {
                    const viewMode = localStorage.getItem('reservationViewMode');
                    if (viewMode) {
                        this.viewMode = viewMode;
                    }
                    
                    const perPage = localStorage.getItem('reservationPerPage');
                    if (perPage) {
                        this.perPage = parseInt(perPage);
                    }
                },

                saveUserPreferences() {
                    localStorage.setItem('reservationViewMode', this.viewMode);
                    localStorage.setItem('reservationPerPage', this.perPage);
                },

                get filteredReservations() {
                    let filtered = [...this.reservations];
                    
                    // Filtrer par statut
                    if (this.filter !== 'all') {
                        filtered = filtered.filter(res => res.statut === this.filter);
                    }
                    
                    // Recherche
                    if (this.searchQuery.trim() !== '') {
                        const query = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(res => {
                            const reservableName = res.reservable ? res.reservable.nom.toLowerCase() : '';
                            const guideName = res.guide ? 
                                (res.guide.prenom + ' ' + res.guide.nom).toLowerCase() : '';
                            const dates = (this.formatDate(res.date_debut) + ' ' + this.formatDate(res.date_fin)).toLowerCase();
                            
                            return reservableName.includes(query) || 
                                guideName.includes(query) || 
                                dates.includes(query) || 
                                res.statut.toLowerCase().includes(query);
                        });
                    }
                    
                    // Tri
                    switch (this.sortBy) {
                        case 'date':
                            filtered.sort((a, b) => {
                                return this.sortOrder === 'desc' 
                                    ? new Date(b.date_debut) - new Date(a.date_debut)
                                    : new Date(a.date_debut) - new Date(b.date_debut);
                            });
                            break;
                        case 'date_asc':
                            filtered.sort((a, b) => new Date(a.date_debut) - new Date(b.date_debut));
                            break;
                        case 'site':
                            filtered.sort((a, b) => {
                                const nameA = a.reservable ? a.reservable.nom.toLowerCase() : 'z';
                                const nameB = b.reservable ? b.reservable.nom.toLowerCase() : 'z';
                                return this.sortOrder === 'asc' 
                                    ? nameA.localeCompare(nameB)
                                    : nameB.localeCompare(nameA);
                            });
                            break;
                        case 'statut':
                            filtered.sort((a, b) => {
                                return this.sortOrder === 'asc' 
                                    ? a.statut.localeCompare(b.statut)
                                    : b.statut.localeCompare(a.statut);
                            });
                            break;
                    }
                    
                    return filtered;
                },

                get paginatedReservations() {
                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + this.perPage;
                    return this.filteredReservations.slice(start, end);
                },

                get totalPages() {
                    return Math.ceil(this.filteredReservations.length / this.perPage);
                },

                get paginationItems() {
                    // Créer un tableau de pagination avec ellipses pour les grandes pages
                    let items = [];
                    if (this.totalPages <= 7) {
                        // Afficher toutes les pages si moins de 7
                        for (let i = 1; i <= this.totalPages; i++) {
                            items.push(i);
                        }
                    } else {
                        // Toujours afficher la première page
                        items.push(1);
                        
                        // Afficher les pages autour de la page actuelle
                        if (this.currentPage <= 3) {
                            // Si on est près du début
                            for (let i = 2; i <= 5; i++) {
                                items.push(i);
                            }
                            items.push('...');
                            items.push(this.totalPages);
                        } else if (this.currentPage >= this.totalPages - 2) {
                            // Si on est près de la fin
                            items.push('...');
                            for (let i = this.totalPages - 4; i <= this.totalPages; i++) {
                                items.push(i);
                            }
                        } else {
                            // Quelque part au milieu
                            items.push('...');
                            for (let i = this.currentPage - 1; i <= this.currentPage + 1; i++) {
                                items.push(i);
                            }
                            items.push('...');
                            items.push(this.totalPages);
                        }
                    }
                    return items;
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('fr-FR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                },

                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        window.scrollTo(0, 0);
                    }
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                        window.scrollTo(0, 0);
                    }
                },

                goToPage(page) {
                    if (page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                        window.scrollTo(0, 0);
                    }
                },

                toggleSort(field) {
                    if (this.sortBy === field) {
                        // Inverser l'ordre si on clique sur la même colonne
                        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
                    } else {
                        // Nouvelle colonne, réinitialiser l'ordre
                        this.sortBy = field;
                        this.sortOrder = 'asc';
                    }
                },

                confirmCancel(id) {
                    this.reservationToCancel = id;
                    this.showCancelModal = true;
                },

                cancelModal() {
                    this.showCancelModal = false;
                    this.reservationToCancel = null;
                },

                displayNotification(message) {
                    this.notificationMessage = message;
                    this.showNotification = true;
                    setTimeout(() => {
                        this.showNotification = false;
                    }, 5000);
                }
            }
        }
    </script>
    <script id="reservations-data" type="application/json">
    @if(isset($reservations))
        @if(method_exists($reservations, 'toArray'))
            {!! json_encode($reservations->toArray()['data'] ?? $reservations->toArray()) !!}
        @else
            {!! json_encode($reservations) !!}
        @endif
    @else
        []
    @endif
</script>
@endsection