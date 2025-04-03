@extends('layouts.proprietaire')

@section('content')
<div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all duration-300">
    <!-- Header avec animation d'entrée -->
    <div 
        x-data="{ shown: false }" 
        x-init="setTimeout(() => shown = true, 100)" 
        :class="shown ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
        class="flex justify-between items-center mb-8 transition-all duration-500 ease-out"
    >
        <h1 class="text-3xl font-bold text-gray-900 relative overflow-hidden group">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                Mes Hébergements
            </span>
            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        </h1>
        <a 
            href="{{ route('proprietaire.hebergements.create') }}" 
            class="relative overflow-hidden group bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-lg font-medium transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl"
        >
            <span class="relative z-10 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Ajouter un hébergement
            </span>
            <span class="absolute inset-0 bg-white opacity-20 transform -translate-x-full skew-x-12 group-hover:translate-x-0 group-hover:skew-x-0 transition-all duration-700"></span>
        </a>
    </div>

    <!-- Notification de succès améliorée -->
    @if(session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4"
            x-init="setTimeout(() => show = false, 5000)" 
            class="bg-gradient-to-r from-green-50 to-teal-50 border-l-4 border-green-500 text-green-700 p-5 mb-8 rounded-r-lg shadow-md" 
            role="alert"
        >
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="absolute top-3 right-3 text-green-700 hover:text-green-900 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    @endif

    <div 
        x-data="{ 
            hebergements: {{ Illuminate\Support\Js::from($hebergements) }},
            filteredHebergements: {{ Illuminate\Support\Js::from($hebergements) }},
            searchTerm: '',
            filterType: 'all',
            filterStatus: 'all',
            sortBy: 'nom',
            sortDirection: 'asc',
            isFiltering: false,
            view: localStorage.getItem('hebergementView') || 'grid',
            
            filterHebergements() {
                this.isFiltering = true;
                setTimeout(() => {
                    this.filteredHebergements = this.hebergements.filter(h => {
                        const matchesSearch = this.searchTerm === '' || 
                            h.nom.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            h.ville.toLowerCase().includes(this.searchTerm.toLowerCase());
                        
                        const matchesType = this.filterType === 'all' || 
                            h.type_hebergement_id == this.filterType;
                        
                        const matchesStatus = this.filterStatus === 'all' || 
                            (this.filterStatus === 'available' && h.disponibilite) ||
                            (this.filterStatus === 'unavailable' && !h.disponibilite);
                        
                        return matchesSearch && matchesType && matchesStatus;
                    });
                    
                    // Trier les résultats
                    this.sortHebergements();
                    this.isFiltering = false;
                }, 300);
            },
            
            sortHebergements() {
                this.filteredHebergements.sort((a, b) => {
                    let valA = a[this.sortBy];
                    let valB = b[this.sortBy];
                    
                    // Traitement spécial pour certains champs
                    if (this.sortBy === 'prix_min') {
                        valA = parseFloat(valA);
                        valB = parseFloat(valB);
                    }
                    
                    if (valA < valB) return this.sortDirection === 'asc' ? -1 : 1;
                    if (valA > valB) return this.sortDirection === 'asc' ? 1 : -1;
                    return 0;
                });
            },
            
            toggleSort(field) {
                if (this.sortBy === field) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortBy = field;
                    this.sortDirection = 'asc';
                }
                this.sortHebergements();
            },
            
            setView(viewType) {
                this.view = viewType;
                localStorage.setItem('hebergementView', viewType);
            },
            
            get resultsCount() {
                return this.filteredHebergements.length;
            }
        }" 
        x-init="
            $watch('searchTerm', () => filterHebergements()); 
            $watch('filterType', () => filterHebergements()); 
            $watch('filterStatus', () => filterHebergements());
        "
    >
        <!-- Panneau de filtres amélioré -->
        <div 
            x-data="{ filtersOpen: window.innerWidth >= 768 }"
            x-init="
                $watch('filtersOpen', value => {
                    if (window.innerWidth < 768) {
                        document.body.style.overflow = value ? 'hidden' : '';
                    }
                });
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 768) filtersOpen = true;
                });
            "
            class="mb-6"
        >
            <!-- Barre de recherche et toggle du panneau de filtres sur mobile -->
            <div class="flex items-center justify-between mb-4 md:hidden">
                <div class="relative flex-grow mr-4">
                    <input 
                        type="text" 
                        id="search-mobile" 
                        x-model="searchTerm" 
                        placeholder="Rechercher un hébergement..." 
                        class="pl-10 pr-4 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all duration-200"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <button 
                    @click="filtersOpen = !filtersOpen"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg flex items-center transition-colors duration-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                    </svg>
                    Filtres
                </button>
            </div>
            
            <!-- Panneau de filtres -->
            <div 
                x-show="filtersOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-4"
                class="md:grid md:grid-cols-12 gap-6 bg-white p-5 rounded-xl shadow-md mb-6"
            >
                <!-- Fermer le panneau (mobile seulement) -->
                <div class="flex justify-between items-center mb-4 col-span-12 md:hidden">
                    <h3 class="text-lg font-semibold text-gray-800">Filtres</h3>
                    <button @click="filtersOpen = false" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Recherche -->
                <div class="col-span-12 md:col-span-4 mb-4 md:mb-0">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="search" 
                            x-model="searchTerm" 
                            placeholder="Nom, ville..." 
                            class="pl-10 pr-4 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all duration-200"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Type d'hébergement -->
                <div class="col-span-12 md:col-span-3 mb-4 md:mb-0">
                    <label for="typeFilter" class="block text-sm font-medium text-gray-700 mb-1">Type d'hébergement</label>
                    <div class="relative">
                        <select 
                            id="typeFilter" 
                            x-model="filterType" 
                            class="pl-4 pr-10 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 appearance-none transition-all duration-200"
                        >
                            <option value="all">Tous les types</option>
                            @foreach(App\Models\TypeHebergement::all() as $type)
                                <option value="{{ $type->id }}">{{ $type->nom }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Disponibilité -->
                <div class="col-span-12 md:col-span-3 mb-4 md:mb-0">
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-1">Disponibilité</label>
                    <div class="relative">
                        <select 
                            id="statusFilter" 
                            x-model="filterStatus" 
                            class="pl-4 pr-10 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 appearance-none transition-all duration-200"
                        >
                            <option value="all">Tous</option>
                            <option value="available">Disponible</option>
                            <option value="unavailable">Non disponible</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Options de tri -->
                <div class="col-span-12 md:col-span-2">
                    <label for="sortBy" class="block text-sm font-medium text-gray-700 mb-1">Trier par</label>
                    <div class="relative">
                        <select 
                            id="sortBy" 
                            x-model="sortBy" 
                            @change="sortHebergements()"
                            class="pl-4 pr-10 py-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 appearance-none transition-all duration-200"
                        >
                            <option value="nom">Nom</option>
                            <option value="ville">Ville</option>
                            <option value="prix_min">Prix</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button @click="sortDirection = sortDirection === 'asc' ? 'desc' : 'asc'; sortHebergements()" class="text-gray-500 hover:text-gray-700 focus:outline-none transition-colors duration-200">
                                <template x-if="sortDirection === 'asc'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </template>
                                <template x-if="sortDirection === 'desc'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </template>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Barre d'information et boutons de vue -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4">
            <div 
                x-show="!isFiltering" 
                x-transition:enter="transition ease-out duration-300" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100"
            >
                <p class="text-gray-600 mb-2 md:mb-0">
                    <span class="font-medium" x-text="resultsCount"></span> hébergement<span x-show="resultsCount !== 1">s</span> trouvé<span x-show="resultsCount !== 1">s</span>
                </p>
            </div>
            <div 
                x-show="isFiltering" 
                x-transition:enter="transition ease-out duration-300" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100"
            >
                <div class="flex items-center text-blue-600">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Filtrage en cours...
                </div>
            </div>
            <div class="flex space-x-2">
                <button 
                    @click="setView('grid')" 
                    :class="view === 'grid' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="p-2 rounded-lg transition-colors duration-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </button>
                <button 
                    @click="setView('list')" 
                    :class="view === 'list' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="p-2 rounded-lg transition-colors duration-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Vue grille -->
        <div 
            x-show="view === 'grid'" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            <!-- Message si aucun résultat -->
            <template x-if="filteredHebergements.length === 0">
                <div class="col-span-full py-16 flex flex-col items-center justify-center bg-white rounded-xl shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <p class="text-gray-500 text-center">Aucun hébergement ne correspond à votre recherche.</p>
                    <button 
                        @click="searchTerm = ''; filterType = 'all'; filterStatus = 'all';" 
                        class="mt-4 text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200"
                    >
                        Réinitialiser les filtres
                    </button>
                </div>
            </template>
            
            <!-- Cartes d'hébergement -->
            <template x-for="(hebergement, index) in filteredHebergements" :key="hebergement.id">
                <div 
                    x-data="{ showActions: false }"
                    class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group"
                    :style="`animation-delay: ${index * 50}ms`"
                    x-show="!isFiltering"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                >
                    <div class="relative">
                        <!-- Image placeholder (généralement remplacée par une vraie image) -->
                        <div class="bg-gradient-to-r from-blue-100 to-indigo-100 h-48 w-full flex items-center justify-center overflow-hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        
                        <!-- Badge de disponibilité -->
                        <div 
                            class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold shadow-sm" 
                            :class="hebergement.disponibilite ? 'bg-green-500 text-white' : 'bg-red-500 text-white'"
                            x-text="hebergement.disponibilite ? 'Disponible' : 'Non disponible'"
                        ></div>
                        
                        <!-- Badge de type -->
                        <div class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-medium text-gray-700 shadow-sm">
                            <span x-text="hebergement.type_hebergement?.nom || 'Type inconnu'"></span>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <a 
                            :href="`/proprietaire/hebergements/${hebergement.id}`" 
                            class="block mb-2"
                        >
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 truncate transition-colors duration-200" x-text="hebergement.nom"></h3>
                        </a>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-1">
                            <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span x-text="hebergement.ville" class="truncate"></span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-500 mt-1">
                            <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                            </svg>
                            <span x-text="`${hebergement.prix_min}€ - ${hebergement.prix_max}€`" class="font-medium"></span>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-between items-center">
                        <div class="flex space-x-1">
                            <a 
                                :href="`/proprietaire/hebergements/${hebergement.id}`" 
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors duration-200"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                Voir
                            </a>
                            <a 
                                :href="`/proprietaire/hebergements/${hebergement.id}/edit`" 
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-amber-700 bg-amber-50 hover:bg-amber-100 transition-colors duration-200"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Modifier
                            </a>
                        </div>
                        <form 
                            :action="`/proprietaire/hebergements/${hebergement.id}`" 
                            method="POST" 
                            class="inline-block"
                            @submit.prevent="
                                if(confirm('Êtes-vous sûr de vouloir supprimer cet hébergement?')) {
                                    $el.submit();
                                }
                            "
                        >
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors duration-200"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </template>
        </div>
        
        <!-- Vue liste -->
        <div 
            x-show="view === 'list'" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="bg-white rounded-xl shadow-sm overflow-hidden"
        >
            <!-- Message si aucun résultat -->
            <template x-if="filteredHebergements.length === 0">
                <div class="py-16 flex flex-col items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <p class="text-gray-500 text-center">Aucun hébergement ne correspond à votre recherche.</p>
                    <button 
                        @click="searchTerm = ''; filterType = 'all'; filterStatus = 'all';" 
                        class="mt-4 text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200"
                    >
                        Réinitialiser les filtres
                    </button>
                </div>
            </template>
            
            <!-- Liste d'hébergements -->
            <ul class="divide-y divide-gray-200">
                <template x-for="(hebergement, index) in filteredHebergements" :key="hebergement.id">
                    <li 
                        class="hover:bg-gray-50 transition-colors duration-150"
                        :style="`animation-delay: ${index * 50}ms`"
                        x-show="!isFiltering"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"     
                    >
                        <div class="px-6 py-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <!-- Image placeholder (généralement remplacée par une vraie image) -->
                                    <div class="bg-gradient-to-r from-blue-100 to-indigo-100 h-16 w-16 rounded-lg flex items-center justify-center mr-4 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                    
                                    <div>
                                        <a 
                                            :href="`/proprietaire/hebergements/${hebergement.id}`" 
                                            class="block"
                                        >
                                            <h3 class="text-lg font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200" x-text="hebergement.nom"></h3>
                                        </a>
                                        
                                        <div class="flex items-center mt-1">
                                            <div 
                                                class="px-2 py-0.5 text-xs font-semibold rounded-full mr-2" 
                                                :class="hebergement.disponibilite ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                                x-text="hebergement.disponibilite ? 'Disponible' : 'Non disponible'"
                                            ></div>
                                            
                                            <div class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                                                <span x-text="hebergement.type_hebergement?.nom || 'Type inconnu'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="hidden md:flex items-center space-x-4">
                                    <div class="text-right">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            <span x-text="hebergement.ville"></span>
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-500 mt-1 justify-end">
                                            <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                            </svg>
                                            <span x-text="`${hebergement.prix_min}€ - ${hebergement.prix_max}€`" class="font-medium"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-1">
                                        <a 
                                            :href="`/proprietaire/hebergements/${hebergement.id}`" 
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors duration-200"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                            Voir
                                        </a>
                                        <a 
                                            :href="`/proprietaire/hebergements/${hebergement.id}/edit`" 
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-amber-700 bg-amber-50 hover:bg-amber-100 transition-colors duration-200"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                            Modifier
                                        </a>
                                        <form 
                                            :action="`/proprietaire/hebergements/${hebergement.id}`" 
                                            method="POST" 
                                            class="inline-block"
                                            @submit.prevent="
                                                if(confirm('Êtes-vous sûr de vouloir supprimer cet hébergement?')) {
                                                    $el.submit();
                                                }
                                            "
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors duration-200"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Bouton d'actions pour mobile -->
                                <div class="md:hidden">
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <div>
                                            <button @click="open = !open" type="button" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div 
                                            x-show="open" 
                                            @click.away="open = false"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="transform opacity-100 scale-100"
                                            x-transition:leave-end="transform opacity-0 scale-95"
                                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10"
                                            role="menu"
                                        >
                                            <div class="py-1">
                                                <a :href="`/proprietaire/hebergements/${hebergement.id}`" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700" role="menuitem">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 group-hover:text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Voir les détails
                                                </a>
                                                <a :href="`/proprietaire/hebergements/${hebergement.id}/edit`" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700" role="menuitem">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 group-hover:text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                    Modifier
                                                </a>
                                            </div>
                                            <div class="py-1">
                                                <button 
                                                    @click="$event.preventDefault(); open = false; if(confirm('Êtes-vous sûr de vouloir supprimer cet hébergement?')) { document.getElementById(`delete-form-${hebergement.id}`).submit(); }" 
                                                    class="group flex w-full items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50" 
                                                    role="menuitem"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    Supprimer
                                                </button>
                                                <form :id="`delete-form-${hebergement.id}`" :action="`/proprietaire/hebergements/${hebergement.id}`" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Information additionnelle visible uniquement sur mobile -->
                            <div class="mt-2 md:hidden">
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                    <div class="flex items-center text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="hebergement.ville"></span>
                                    </div>
                                    
                                    <div class="flex items-center text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="`${hebergement.prix_min}€ - ${hebergement.prix_max}€`" class="font-medium"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
@endsection