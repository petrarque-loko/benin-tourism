@extends('layouts.app')

@section('title', 'Découvrez les sites touristiques du Bénin')

@section('content')
<!-- En-tête avec slider -->
<div class="relative">
    <div class="slider">
        <div><img src="{{ asset('images/bg1.jpeg') }}" alt="bg 1" class="w-full h-96 object-cover"></div>
        <div><img src="{{ asset('images/bg2.jpeg') }}" alt="bg 2" class="w-full h-96 object-cover"></div>
        <div><img src="{{ asset('images/bg3.jpeg') }}" alt="bg 3" class="w-full h-96 object-cover"></div>
    </div>
    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-20">
        <div class="container mx-auto px-4 text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-fade-in-down">Explorez les merveilles du Bénin</h1>
            <p class="text-xl md:text-2xl max-w-2xl mx-auto">Découvrez les sites touristiques les plus captivants et organisez votre prochaine aventure</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8" x-data="sitesApp()" x-init="initSliders()">
    <!-- Barre de recherche et filtres -->
    <div class="mb-8 bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Filtrer les sites</h2>
            <button 
                @click="showFilters = !showFilters"
                class="flex items-center text-blue-600 hover:text-blue-800 md:hidden">
                <span x-text="showFilters ? 'Masquer les filtres' : 'Afficher les filtres'"></span>
                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" 
                    :class="{ 'transform rotate-180': showFilters }">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        <div class="flex flex-col md:flex-row md:items-end gap-4" :class="{ 'hidden md:flex': !showFilters }">
            <div class="flex-grow">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <div class="relative">
                    <input 
                        type="text" 
                        id="search" 
                        placeholder="Nom, description ou localisation..."
                        class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        x-model="searchQuery"
                        @input="filterSites()"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-64">
                <label for="categorie" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select 
                    id="categorie" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    x-model="selectedCategory"
                    @change="filterSites()">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Résultats de recherche -->
    <div class="mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">
            <span x-text="filteredSites.length"></span> 
            site<span x-show="filteredSites.length > 1">s</span> 
            trouvé<span x-show="filteredSites.length > 1">s</span>
        </h3>
    </div>

    <!-- Grille des sites touristiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <template x-for="site in paginatedSites" :key="site.id">
            <div class="site-card bg-white rounded-lg shadow-md overflow-hidden transition transform hover:-translate-y-1 hover:shadow-lg duration-300">
                <div class="h-64 bg-gray-200 overflow-hidden">
                    <template x-if="site.medias && site.medias.length > 0">
                        <div class="site-slider-container h-full" :data-site-id="site.id">
                            <template x-for="media in site.medias" :key="media.id">
                                <img 
                                    :src="'{{ asset('storage') }}/' + media.url" 
                                    :alt="site.nom" 
                                    class="w-full h-64 object-cover"
                                >
                            </template>
                        </div>
                    </template>
                    <template x-if="!site.medias || site.medias.length === 0">
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <span class="text-gray-400"></span>
                        </div>
                    </template>
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full" x-text="site.categorie.nom"></span>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="ml-1 text-sm text-gray-600" x-text="site.commentaires_avg_note ? site.commentaires_avg_note.toFixed(1) : 'N/A'"></span>
                            <span class="ml-1 text-sm text-gray-600">(<span x-text="site.commentaires_count"></span>)</span>
                        </div>
                    </div>
                    <a :href="'{{ route('sites.show', '') }}/' + site.id" class="block">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 hover:text-blue-600" x-text="site.nom"></h3>
                    </a>
                    <div class="flex items-start mb-3">
                        <svg class="w-5 h-5 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="ml-2 text-sm text-gray-600" x-text="site.localisation"></span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2" x-text="site.description"></p>
                    <div class="flex items-center justify-between">
                        <a :href="'{{ route('sites.show', '') }}/' + site.id" class="inline-block text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Découvrir
                            <span class="ml-1">→</span>
                        </a>
                        <button 
                            @click="openReservationModal(site.id)"
                            class="inline-block mt-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300">
                            Réserver
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Pagination -->
    <div class="mt-8 text-center" x-show="filteredSites.length > itemsPerPage">
        <template x-if="totalPages > 1">
            <div class="flex flex-wrap justify-center gap-2">
                <button 
                    @click="changePage('prev')"
                    :disabled="currentPage === 1"
                    :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <template x-for="page in pagesArray" :key="page">
                    <button 
                        @click="goToPage(page)"
                        :class="{'bg-blue-600 text-white': currentPage === page, 'bg-gray-200 text-gray-700': currentPage !== page}"
                        class="px-4 py-2 rounded-lg hover:bg-blue-500 hover:text-white transition duration-200">
                        <span x-text="page"></span>
                    </button>
                </template>
                <button 
                    @click="changePage('next')"
                    :disabled="currentPage === totalPages"
                    :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <!-- Message si aucun site trouvé -->
    <div x-show="filteredSites.length === 0" class="text-center py-12 col-span-full">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 class="text-xl font-medium text-gray-800">Aucun site trouvé</h3>
        <p class="mt-2 text-gray-600">Essayez de modifier vos critères de recherche.</p>
    </div>
</div>

<!-- Modal de sélection de dates -->
<div id="datePickerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4">Sélectionnez vos dates</h2>
        <form id="datePickerForm" method="GET" action="">
            <div class="mb-4">
                <label for="date_debut" class="block mb-2">Date de début</label>
                <input type="date" name="date_debut" id="date_debut" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label for="date_fin" class="block mb-2">Date de fin</label>
                <input type="date" name="date_fin" id="date_fin" class="w-full border rounded p-2" required>
            </div>
            <div class="flex justify-between">
                <button type="button" id="cancelDatePicker" class="bg-gray-300 px-4 py-2 rounded">Annuler</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Continuer</button>
            </div>
        </form>
    </div>
</div>

<!-- Section CTA -->
<div class="bg-blue-600 py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Prêt à découvrir le Bénin ?</h2>
        <p class="text-white text-lg mb-8 max-w-2xl mx-auto">
            Planifiez votre prochaine visite avec un guide expert et créez des souvenirs inoubliables.
        </p>
        <a href="" class="cta-button inline-block bg-white text-blue-600 font-bold py-3 px-6 rounded-lg hover:bg-blue-50 transition duration-300">
            Voir les circuits disponibles
        </a>
    </div>
</div>

<script>
    function sitesApp() {
        return {
            sites: @json($sites->items()), // Charger les sites une seule fois
            filteredSites: [],
            searchQuery: '{{ request('search') }}',
            selectedCategory: '{{ request('categorie_id') }}',
            currentPage: 1,
            itemsPerPage: 6,
            showFilters: false,

            init() {
                this.filterSites(); // Initialiser les sites filtrés au chargement
            },

            filterSites() {
                this.currentPage = 1; // Réinitialiser la page lors du filtrage
                this.filteredSites = this.sites.filter(site => {
                    const matchesSearch = site.nom.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                          site.description.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                          site.localisation.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const matchesCategory = this.selectedCategory === '' || site.categorie_id == this.selectedCategory;
                    return matchesSearch && matchesCategory;
                });
            },

            get totalPages() {
                return Math.ceil(this.filteredSites.length / this.itemsPerPage);
            },

            get pagesArray() {
                return Array.from({ length: this.totalPages }, (_, i) => i + 1);
            },

            get paginatedSites() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredSites.slice(start, end);
            },

            goToPage(page) {
                this.currentPage = page;
            },

            changePage(direction) {
                if (direction === 'prev' && this.currentPage > 1) {
                    this.currentPage--;
                } else if (direction === 'next' && this.currentPage < this.totalPages) {
                    this.currentPage++;
                }
            },

            openReservationModal(siteId) {
                const form = document.getElementById('datePickerForm');
                form.action = `{{ route('touriste.reservations.sites.create', '') }}/${siteId}`;
                document.getElementById('datePickerModal').classList.remove('hidden');
            }
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('datePickerModal');
        document.getElementById('cancelDatePicker').addEventListener('click', function() {
            modal.classList.add('hidden');
        });
    });
</script>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* Styles existants */
    .animate-fade-in-down { animation: fade-in-down 1.2s ease-out; }
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .site-card {
        opacity: 0;
        animation: fadeIn 0.5s ease-in forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .site-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }
    .cta-button {
        animation: pulse 2s infinite;
        background-color: #f59e0b;
        color: white;
    }
    .cta-button:hover { background-color: #d97706; }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .slider, .site-slider {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    .slider .slick-slide, .site-slider .slick-slide {
        position: relative;
        height: 100%;
    }
    .site-slider .slick-list, .site-slider .slick-track { height: 100%; }
    .site-slider .slick-dots {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }
    .site-slider .slick-dots li button:before { color: white; opacity: 0.7; }
    .site-slider .slick-dots li.slick-active button:before { opacity: 1; }
    .site-slider .slick-prev, .site-slider .slick-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        width: 30px;
        height: 30px;
    }
    .site-slider .slick-prev { left: 10px; }
    .site-slider .slick-next { right: 10px; }
    .site-slider .slick-prev:before, .site-slider .slick-next:before {
        font-size: 24px;
        text-shadow: 0 0 3px rgba(0,0,0,0.5);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    function initSliders() {
        setTimeout(() => {
            // Initialisation du slider principal
            if ($('.slider').length) {
                $('.slider').slick({
                    autoplay: true,
                    autoplaySpeed: 3000,
                    dots: true,
                    arrows: true,
                    fade: true,
                    infinite: true,
                    speed: 500,
                    cssEase: 'linear'
                });
            }
            
            // Pour les sliders de sites
            $('.site-slider-container').each(function() {
                const $container = $(this);
                const $images = $container.children('img').detach();
                
                // Ne créer un slider que s'il y a des images
                if ($images.length > 0) {
                    // Créer un nouveau div pour le slider
                    const $slider = $('<div class="site-slider h-full"></div>');
                    $container.append($slider);
                    
                    // Ajouter chaque image dans un div à l'intérieur du slider
                    $images.each(function() {
                        const $slide = $('<div class="h-full"></div>');
                        $slide.append($(this));
                        $slider.append($slide);
                    });
                    
                    // N'initialiser Slick que s'il y a plus d'une image
                    if ($images.length > 1) {
                        $slider.slick({
                            autoplay: true,
                            autoplaySpeed: 3000,
                            dots: true,
                            arrows: true,
                            infinite: true,
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            adaptiveHeight: false
                        });
                    }
                }
            });
        }, 20);
    }
</script>
@endpush