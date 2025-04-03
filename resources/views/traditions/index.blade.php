@extends('layouts.app')

@section('title', 'Traditions et Coutumes du Bénin')

@section('content')
<div x-data="{
    traditions: {{ Js::from($traditions) }},
    categories: {{ Js::from($categories) }},
    filteredTraditions: [],
    searchQuery: '{{ request('search') ?? '' }}',
    selectedCategory: '{{ request('categorie_id') ?? '' }}',
    currentPage: 1,
    perPage: 12,
    currentSlide: 0,
    sliderImages: [
        '/images/traditions/slider1.jpg',
        '/images/traditions/slider2.jpg',
        '/images/traditions/slider3.jpg'
    ],
    sliderTexts: [  
        'Découvrez les traditions qui façonnent notre patrimoine culturel',
        'Explorez la richesse de notre héritage traditionnel',
        'Plongez dans l\'histoire de nos coutumes ancestrales'
    ],
    get paginatedTraditions() {
        const start = (this.currentPage - 1) * this.perPage;
        const end = start + this.perPage;
        return this.filteredTraditions.slice(start, end);
    },
    get totalPages() {
        return Math.ceil(this.filteredTraditions.length / this.perPage);
    },
    get pagesArray() {
        return Array.from({ length: this.totalPages }, (_, i) => i + 1);
    },
    init() {
        this.filteredTraditions = [...this.traditions];
        this.startSliderInterval();
        this.setupIntersectionObserver();
        this.filterTraditions(); // Apply initial filters if query parameters exist
    },
    filterTraditions() {
        this.currentPage = 1;
        this.filteredTraditions = this.traditions.filter(tradition => {
            const searchLower = this.searchQuery.toLowerCase();
            const matchesSearch = this.searchQuery === '' || 
                tradition.titre.toLowerCase().includes(searchLower) || 
                tradition.resume.toLowerCase().includes(searchLower);
            const categoryId = tradition.categorie_id ? String(tradition.categorie_id) : '';
            const matchesCategory = this.selectedCategory === '' || categoryId === this.selectedCategory;
            return matchesSearch && matchesCategory;
        });
        
        // Permet au DOM de se mettre à jour avant de réinitialiser l'observateur
        setTimeout(() => {
            this.reapplyObserver();
        }, 50);
    },
    startSliderInterval() {
        setInterval(() => {
            this.currentSlide = (this.currentSlide + 1) % this.sliderImages.length;
        }, 5000);
    },
    setupIntersectionObserver() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-10');
                }
            });
        }, { threshold: 0.1 });

        // Réinitialiser toutes les cartes à l'état invisible
        document.querySelectorAll('.tradition-card').forEach(card => {
            card.classList.remove('opacity-100', 'translate-y-0');
            card.classList.add('opacity-0', 'translate-y-10');
            observer.observe(card);
        });
    },
    reapplyObserver() {
        // Réinitialise l'affichage des cartes
        document.querySelectorAll('.tradition-card').forEach(card => {
            card.classList.remove('opacity-100', 'translate-y-0');
            card.classList.add('opacity-0', 'translate-y-10');
        });
        
        // Réapplique l'observateur
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-10');
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.tradition-card').forEach(card => {
            observer.observe(card);
        });
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
    }
}" x-init="init">

    <!-- Hero Slider Section -->
    <div class="relative h-96 overflow-hidden">
        <template x-for="(image, index) in sliderImages" :key="index">
            <div 
                class="absolute inset-0 w-full h-full bg-cover bg-center transition-opacity duration-1000"
                :style="`background-image: url('${image}')`"
                :class="currentSlide === index ? 'opacity-100' : 'opacity-0'"
            >
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                <div class="flex items-center justify-center h-full">
                    <h1 
                        class="text-4xl md:text-5xl text-white font-bold text-center px-6 max-w-4xl transition-all duration-500"
                        :class="currentSlide === index ? 'opacity-100 scale-100' : 'opacity-0 scale-90'"
                        x-text="sliderTexts[index]"
                    ></h1>
                </div>
            </div>
        </template>
        <div class="absolute bottom-5 left-0 right-0 flex justify-center space-x-3">
            <template x-for="(_, index) in sliderImages" :key="index">
                <button 
                    @click="currentSlide = index" 
                    class="w-3 h-3 rounded-full transition-all duration-300"
                    :class="currentSlide === index ? 'bg-white scale-125' : 'bg-white bg-opacity-50 scale-100'"
                ></button>
            </template>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="bg-gradient-to-r from-indigo-900 to-purple-900 py-8 px-4 md:px-8 shadow-lg">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-6">
                <div class="flex-1">
                    <label for="search" class="block text-white text-sm font-medium mb-2">
                        <svg class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Rechercher
                    </label>
                    <input 
                        id="search" 
                        type="text" 
                        x-model="searchQuery" 
                        @input="filterTraditions()" 
                        placeholder="Rechercher une tradition ou coutume..." 
                        class="w-full px-4 py-2 rounded-lg focus:ring focus:ring-indigo-300 focus:outline-none transition-all duration-300"
                    >
                </div>
                <div class="md:w-64">
                    <label for="category" class="block text-white text-sm font-medium mb-2">
                        <svg class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Catégorie
                    </label>
                    <select 
                        id="category" 
                        x-model="selectedCategory" 
                        @change="filterTraditions()" 
                        class="w-full px-4 py-2 rounded-lg focus:ring focus:ring-indigo-300 focus:outline-none transition-all duration-300"
                    >
                        <option value="">Toutes les catégories</option>
                        <template x-for="category in categories" :key="category.id">
                            <option :value="category.id" x-text="category.nom"></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Traditions Grid Section -->
    <div class="py-12 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            <div 
                x-show="filteredTraditions.length === 0"
                class="bg-white p-8 rounded-xl shadow-md text-center transition-all duration-500"
            >
                <svg class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune tradition trouvée</h3>
                <p class="text-gray-500">Modifiez vos critères de recherche ou sélectionnez une autre catégorie.</p>
            </div>

            <div x-effect="$nextTick(() => { setupIntersectionObserver(); })" x-show="paginatedTraditions.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <template x-for="tradition in paginatedTraditions" :key="tradition.id">
                    <div class="tradition-card bg-white rounded-xl shadow-lg overflow-hidden opacity-0 translate-y-10 transition-all duration-500 hover:scale-[1.02] hover:shadow-xl">
                        <div x-data="{
                            mediaIndex: 0,
                            mediaInterval: null,
                            mediaCount() { return tradition.medias ? tradition.medias.length : 0 },
                            nextMedia() { this.mediaIndex = (this.mediaIndex + 1) % this.mediaCount(); },
                            prevMedia() { this.mediaIndex = (this.mediaIndex - 1 + this.mediaCount()) % this.mediaCount(); },
                            init() { if (this.mediaCount() > 1) this.mediaInterval = setInterval(() => this.nextMedia(), 3000); },
                            stopInterval() { if (this.mediaInterval) clearInterval(this.mediaInterval); },
                            startInterval() { if (this.mediaCount() > 1) this.mediaInterval = setInterval(() => this.nextMedia(), 3000); }
                        }" x-init="init()" @mouseover="stopInterval()" @mouseleave="startInterval()">
                            <div class="relative h-64 bg-gray-200">
                                <div x-show="mediaCount() === 0" class="w-full h-full flex items-center justify-center bg-gradient-to-r from-indigo-500 to-purple-600">
                                    <svg class="h-16 w-16 text-white opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <template x-if="mediaCount() > 0">
                                    <template x-for="(media, index) in tradition.medias" :key="index">
                                        <div 
                                            x-show="mediaIndex === index"
                                            class="absolute inset-0 w-full h-full bg-cover bg-center transition-opacity duration-500"
                                            :style="`background-image: url('${media.url}')`"
                                        ></div>
                                    </template>
                                </template>
                                <template x-if="mediaCount() > 1">
                                    <div>
                                        <button @click.prevent="prevMedia()" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <button @click.prevent="nextMedia()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                        <div class="absolute bottom-3 left-0 right-0 flex justify-center space-x-2">
                                            <template x-for="(_, imgIndex) in tradition.medias" :key="imgIndex">
                                                <button 
                                                    @click.prevent="mediaIndex = imgIndex" 
                                                    class="w-2 h-2 rounded-full transition-all duration-300"
                                                    :class="mediaIndex === imgIndex ? 'bg-white scale-125' : 'bg-white bg-opacity-50'"
                                                ></button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                <div class="absolute top-3 left-3">
                                    <span class="px-3 py-1 bg-indigo-600 bg-opacity-90 text-white text-sm font-medium rounded-full" x-text="tradition.categorie.nom"></span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h2 class="text-xl font-bold mb-2 text-gray-800" x-text="tradition.titre"></h2>
                            <p class="text-gray-600 mb-4 line-clamp-2" x-text="tradition.resume"></p>
                            <div class="flex justify-between items-center mt-4">
                                <a :href="'/traditions_coutumes/' + tradition.id" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
                                    <span>Lire plus</span>
                                    <svg class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                                <div class="text-sm text-gray-500" x-text="tradition.created_at ? new Date(tradition.created_at).toLocaleDateString() : ''"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center" x-show="filteredTraditions.length > perPage">
                <div class="flex flex-wrap justify-center gap-2">
                    <button 
                        @click="changePage('prev')"
                        :disabled="currentPage === 1"
                        :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <template x-for="page in pagesArray" :key="page">
                        <button 
                            @click="goToPage(page)"
                            :class="{'bg-indigo-600 text-white': currentPage === page, 'bg-gray-200 text-gray-700': currentPage !== page}"
                            class="px-4 py-2 rounded-md hover:bg-indigo-500 hover:text-white transition duration-200"
                        >
                            <span x-text="page"></span>
                        </button>
                    </template>
                    <button 
                        @click="changePage('next')"
                        :disabled="currentPage === totalPages"
                        :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Section "En savoir plus" -->
    <div class="bg-white py-16 px-4 md:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Découvrez notre patrimoine culturel</h2>
            <p class="text-gray-600 mb-8">Notre collection de traditions et coutumes est constamment mise à jour. Explorez notre riche héritage culturel et découvrez les pratiques qui ont façonné notre identité.</p>
            <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-300 inline-flex items-center">
                En savoir plus sur notre démarche
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Section statistiques -->
    <div class="bg-gradient-to-r from-purple-900 to-indigo-900 py-16 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="bg-white bg-opacity-10 p-8 rounded-xl backdrop-filter backdrop-blur-sm">
                    <div class="text-4xl font-bold text-white mb-2" x-text="traditions.length"></div>
                    <div class="text-lg text-purple-200">Traditions répertoriées</div>
                </div>
                <div class="bg-white bg-opacity-10 p-8 rounded-xl backdrop-filter backdrop-blur-sm">
                    <div class="text-4xl font-bold text-white mb-2" x-text="categories.length"></div>
                    <div class="text-lg text-purple-200">Catégories</div>
                </div>
                <div class="bg-white bg-opacity-10 p-8 rounded-xl backdrop-filter backdrop-blur-sm">
                    <div class="text-4xl font-bold text-white mb-2" x-text="traditions.reduce((total, t) => total + (t.medias ? t.medias.length : 0), 0)"></div>
                    <div class="text-lg text-purple-200">Médias</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Appel à contribution -->
    <div class="py-16 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="md:flex">
                    <div class="md:w-1/2 p-8 md:p-12">
                        <h2 class="text-3xl font-bold text-gray-800 mb-4">Contribuez à notre collection</h2>
                        <p class="text-gray-600 mb-6">Connaissez-vous une tradition ou coutume qui n'est pas encore répertoriée ? Partagez vos connaissances et contribuez à la préservation de notre patrimoine culturel.</p>
                        <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-300 inline-flex items-center">
                            Proposer une tradition
                            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                    </div>
                    <div class="md:w-1/2 bg-indigo-600">
                        <div class="h-full flex items-center justify-center p-8 md:p-12">
                            <svg class="h-32 w-32 text-white opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush