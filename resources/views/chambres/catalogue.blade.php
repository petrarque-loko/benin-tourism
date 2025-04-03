@extends('layouts.app')


@section('content')
    <style>
        [x-cloak] { display: none !important; }
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .carousel-container { position: relative; overflow: hidden; }
        .transition-all { transition: all 0.3s ease; }
        .hidden { display: none; }
        .block { display: block; }
    </style>
    <div class=" font-sans"
    >
        <div x-data="chambresCatalogue()" x-init="init()" class="min-h-screen">
            <!-- Hero section -->
            <div class="relative bg-cover bg-center h-64 md:h-96" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80')">
                <div class="absolute inset-0 bg-black opacity-50"></div>
                <div class="relative container mx-auto px-4 h-full flex items-center justify-center">
                    <div class="text-center">
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Découvrez nos chambres</h1>
                        <p class="text-xl text-white mb-8">Trouvez l'hébergement idéal pour votre séjour</p>
                        <div class="bg-white p-4 rounded-lg shadow-lg max-w-3xl mx-auto">
                            <div class="flex flex-col md:flex-row md:space-x-4">
                                <div class="flex-grow mb-3 md:mb-0">
                                    <input 
                                        type="text" 
                                        x-model="searchTerm" 
                                        @keyup.debounce.300ms="search()"
                                        placeholder="Rechercher une chambre, un hôtel, une ville..." 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                </div>
                                <button 
                                    @click="search()" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-md transition duration-300"
                                >
                                    Rechercher
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="container mx-auto px-4 py-8">
                <!-- Filtres (en haut, centrés) -->
                <div class="mb-8 flex justify-center">
                    <div class="flex items-start">
                        <!-- Onglet pour agrandir/réduire -->
                        <button 
                            @click="toggleFilters()" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md transition duration-300 flex items-center z-10"
                        >
                            <span x-text="filtersExpanded ? 'Masquer' : 'Afficher les options de filtrage'"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-bind:class="{'transform rotate-180': filtersExpanded}">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <!-- Conteneur des filtres -->
                        <div 
                            x-bind:class="filtersExpanded ? 'block' : 'hidden'" 
                            class="bg-white rounded-lg shadow-md p-4 ml-4 transition-all duration-300 max-w-4xl"
                        >
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Type d'hébergement -->
                                <div>
                                    <h3 class="font-semibold text-gray-700 mb-2">Type d'hébergement</h3>
                                    <select 
                                        x-model="filters.type_hebergement_id" 
                                        @change="search()"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="">Tous les types</option>
                                        @foreach($typesHebergement as $type)
                                            <option value="{{ $type->id }}">{{ $type->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Type de chambre -->
                                <div>
                                    <h3 class="font-semibold text-gray-700 mb-2">Type de chambre</h3>
                                    <select 
                                        x-model="filters.type_chambre" 
                                        @change="search()"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="">Tous les types</option>
                                        @foreach($typeChambres as $type)
                                            <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Capacité -->
                                <div>
                                    <h3 class="font-semibold text-gray-700 mb-2">Capacité</h3>
                                    <div class="flex items-center">
                                        <input 
                                            type="number" 
                                            x-model="filters.capacite" 
                                            min="1" 
                                            max="10" 
                                            @change="search()"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                        <span class="ml-2 text-gray-600">personnes</span>
                                    </div>
                                </div>

                                <!-- Ville -->
                                <div>
                                    <h3 class="font-semibold text-gray-700 mb-2">Ville</h3>
                                    <select 
                                        x-model="filters.ville" 
                                        @change="search()"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="">Toutes les villes</option>
                                        @foreach($villes as $ville)
                                            <option value="{{ $ville }}">{{ $ville }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Prix -->
                                <div>
                                    <h3 class="font-semibold text-gray-700 mb-2">Prix</h3>
                                    <div class="mb-2">
                                        <label class="block text-sm text-gray-600 mb-1">Minimum</label>
                                        <input 
                                            type="number" 
                                            x-model="filters.prix_min" 
                                            min="0" 
                                            @change="search()"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Maximum</label>
                                        <input 
                                            type="number" 
                                            x-model="filters.prix_max" 
                                            min="0" 
                                            @change="search()"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                    </div>
                                </div>

                                <!-- Dates de disponibilité -->
                                <div>
                                    <h3 class="font-semibold text-gray-700 mb-2">Dates</h3>
                                    <div class="mb-2">
                                        <label class="block text-sm text-gray-600 mb-1">Arrivée</label>
                                        <input 
                                            type="date" 
                                            x-model="filters.date_debut" 
                                            @change="search()"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Départ</label>
                                        <input 
                                            type="date" 
                                            x-model="filters.date_fin" 
                                            @change="search()"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                    </div>
                                </div>

                                <!-- Tri -->
                                <div>
                                    <h3 class="font-semibold text-gray-700 mb-2">Tri</h3>
                                    <select 
                                        x-model="filters.tri" 
                                        @change="search()"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="asc">Prix croissant</option>
                                        <option value="desc">Prix décroissant</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button 
                                    @click="resetFilters()" 
                                    class="text-sm text-blue-600 hover:text-blue-800 transition duration-300"
                                >
                                    Réinitialiser tous les filtres
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Résultats -->
                <div>
                    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div class="mb-4 sm:mb-0">
                            <span class="font-semibold text-gray-700" x-text="`${totalChambres} résultat(s) trouvé(s)`"></span>
                        </div>
                        <div class="flex items-center">
                            <label class="mr-2 text-gray-700">Affichage:</label>
                            <div class="flex space-x-2">
                                <button 
                                    @click="viewMode = 'grid'" 
                                    :class="{'bg-blue-600 text-white': viewMode === 'grid', 'bg-gray-200 text-gray-700': viewMode !== 'grid'}"
                                    class="px-3 py-1 rounded-md transition duration-300"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                </button>
                                <button 
                                    @click="viewMode = 'list'" 
                                    :class="{'bg-blue-600 text-white': viewMode === 'list', 'bg-gray-200 text-gray-700': viewMode !== 'list'}"
                                    class="px-3 py-1 rounded-md transition duration-300"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Indicateur de chargement -->
                    <div x-show="isLoading" class="flex justify-center items-center h-64">
                        <svg class="animate-spin h-12 w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Liste des chambres en mode grille -->
                    <div x-show="!isLoading && viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <template x-for="chambre in chambres" :key="chambre.id">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 transform hover:-translate-y-1 hover:shadow-lg fade-in">
                                <div class="carousel-container h-48">
                                    <div x-data="{ currentImage: 0 }" class="relative h-full">
                                        <template x-for="(media, index) in chambre.medias" :key="index">
                                            <img 
                                                x-show="currentImage === index" 
                                                x-bind:src="'/storage/' + media.url || '/images/placeholder-chambre.jpg'"
                                                class="w-full h-full object-cover transition-opacity duration-500"
                                                x-bind:alt="chambre.titre"
                                            >
                                        </template>
                                        <button 
                                            @click="currentImage = (currentImage - 1 + chambre.medias.length) % chambre.medias.length" 
                                            class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 p-2 rounded-full"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <button 
                                            @click="currentImage = (currentImage + 1) % chambre.medias.length" 
                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 p-2 rounded-full"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="absolute bottom-0 right-0 bg-white py-1 px-3 m-2 rounded-md text-sm font-bold text-blue-600">
                                        <span x-text="`${chambre.prix} €`"></span>
                                        <span class="text-xs text-gray-500">/nuit</span>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="flex justify-between mb-2">
                                        <h3 class="text-lg font-semibold text-gray-800" x-text="chambre.titre"></h3>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700 ml-1" x-text="chambre.note_moyenne ? chambre.note_moyenne.toFixed(1) : 'N/A'"></span>
                                        </div>
                                    </div>
                                    <p class="text-gray-500 text-sm mb-3" x-text="chambre.hebergement ? chambre.hebergement.nom + ', ' + chambre.hebergement.ville : ''"></p>
                                    <div class="flex items-center text-sm text-gray-600 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span x-text="`${chambre.capacite} personne(s)`"></span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <span x-text="chambre.type_chambre"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <template x-if="chambre.est_disponible">
                                            <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">Disponible</span>
                                        </template>
                                        <template x-if="!chambre.est_disponible">
                                            <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded-full">Indisponible</span>
                                        </template>
                                        <a 
                                            x-bind:href="`/hebergements/${chambre.id}`"
                                            class="text-blue-600 hover:text-blue-800 font-medium text-sm transition duration-300"
                                        >
                                            Voir détails →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Liste des chambres en mode liste -->
                    <div x-show="!isLoading && viewMode === 'list'" class="space-y-4">
                        <template x-for="chambre in chambres" :key="chambre.id">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row fade-in">
                                <div class="md:w-1/3 h-48 md:h-auto relative">
                                    <div x-data="{ currentImage: 0 }" class="relative h-full">
                                        <template x-for="(media, index) in chambre.medias" :key="index">
                                            <img 
                                                x-show="currentImage === index" 
                                                x-bind:src="'/storage/' + media.url || '/images/placeholder-chambre.jpg'"
                                                class="w-full h-full object-cover transition-opacity duration-500"
                                                x-bind:alt="chambre.titre"
                                            >
                                        </template>
                                        <button 
                                            @click="currentImage = (currentImage - 1 + chambre.medias.length) % chambre.medias.length" 
                                            class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 p-2 rounded-full"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <button 
                                            @click="currentImage = (currentImage + 1) % chambre.medias.length" 
                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 p-2 rounded-full"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-4 md:w-2/3 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <h3 class="text-lg font-semibold text-gray-800" x-text="chambre.titre"></h3>
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                                </svg>
                                                <span class="text-sm font-medium text-gray-700 ml-1" x-text="chambre.note_moyenne ? chambre.note_moyenne.toFixed(1) : 'N/A'"></span>
                                            </div>
                                        </div>
                                        <p class="text-gray-500 text-sm mb-3" x-text="chambre.hebergement ? chambre.hebergement.nom + ', ' + chambre.hebergement.ville : ''"></p>
                                        <p class="text-gray-600 mb-4 line-clamp-2" x-text="chambre.description"></p>
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <span x-text="`${chambre.capacite} personne(s)`"></span>
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                </svg>
                                                <span x-text="chambre.type_chambre"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <template x-if="chambre.est_disponible">
                                            <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">Disponible</span>
                                        </template>
                                        <template x-if="!chambre.est_disponible">
                                            <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded-full">Indisponible</span>
                                        </template>
                                        <a 
                                            x-bind:href="`/hebergements/${chambre.id}`"
                                            class="text-blue-600 hover:text-blue-800 font-medium text-sm transition duration-300"
                                        >
                                            Voir détails →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Message si aucun résultat -->
                    <div x-show="!isLoading && chambres.length === 0" class="flex flex-col items-center justify-center h-64 bg-white rounded-lg shadow-md p-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun résultat trouvé</h3>
                        <p class="text-gray-500 text-center mb-4">Essayez de modifier vos critères de recherche pour trouver des chambres disponibles.</p>
                        <button 
                            @click="resetFilters()" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-300"
                        >
                            Réinitialiser les filtres
                        </button>
                    </div>

                    <!-- Pagination -->
                    <div x-show="!isLoading && chambres.length > 0" class="mt-8">
                        <div class="flex justify-center">
                            {{ $chambres->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js script pour la gestion du catalogue -->
    <script>
        function chambresCatalogue() {
            return {
                chambres: @json($chambres->items()),
                totalChambres: @json($chambres->total()),
                isLoading: false,
                viewMode: 'grid', // 'grid' ou 'list'
                searchTerm: '',
                filters: {
                    type_hebergement_id: '',
                    type_chambre: '',
                    capacite: '',
                    ville: '',
                    prix_min: '',
                    prix_max: '',
                    date_debut: '',
                    date_fin: '',
                    tri: 'asc'
                },
                filtersExpanded: false, // Réduit par défaut
                
                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    for (const [key, value] of Object.entries(this.filters)) {
                        if (urlParams.has(key)) {
                            this.filters[key] = urlParams.get(key);
                        }
                    }
                    if (urlParams.has('search_term')) {
                        this.searchTerm = urlParams.get('search_term');
                    }
                    this.viewMode = localStorage.getItem('viewMode') || 'grid';
                    this.$watch('viewMode', (value) => {
                        localStorage.setItem('viewMode', value);
                    });
                },
                
                toggleFilters() {
                    this.filtersExpanded = !this.filtersExpanded;
                },
                
                search() {
                    this.isLoading = true;
                    const params = { ...this.filters, search_term: this.searchTerm };
                    Object.keys(params).forEach(key => 
                        (params[key] === '' || params[key] === null) && delete params[key]
                    );
                    const queryString = new URLSearchParams(params).toString();
                    window.history.replaceState(null, null, `?${queryString}`);
                    fetch(`/hebergements/search?${queryString}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Erreur lors de la recherche');
                        return response.json();
                    })
                    .then(data => {
                        this.chambres = data.chambres || [];
                        this.totalChambres = data.count || 0;
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        this.isLoading = false;
                        alert('Une erreur est survenue lors de la recherche. Veuillez réessayer.');
                    });
                },
                
                resetFilters() {
                    Object.keys(this.filters).forEach(key => {
                        this.filters[key] = '';
                    });
                    this.searchTerm = '';
                    this.search();
                }
            };
        }
    </script> 
@endsection