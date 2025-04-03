@extends('layouts.app')

@section('content')
<div x-data="evenementsApp()" class=" min-h-screen">
    <!-- Hero Slider -->
    <div class="relative h-96 overflow-hidden">
        <div class="absolute inset-0 w-full h-full">
            <!-- Slider images -->
            <template x-for="(slide, index) in heroSlides" :key="index">
                <div 
                    x-show="currentSlide === index"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 transform scale-105"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    :style="`background-image: url('${slide.image}'); background-size: cover; background-position: center;`"
                    class="absolute inset-0 w-full h-full">
                    <div class="absolute inset-0  bg-opacity-40"></div>
                    <div class="container mx-auto h-full flex items-center justify-center px-4">
                        <div class="text-center max-w-3xl">
                            <h1 
                                class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4"
                                x-text="slide.title"
                                x-transition:enter="transition ease-out delay-300 duration-500"
                                x-transition:enter-start="opacity-0 transform translate-y-10"
                                x-transition:enter-end="opacity-100 transform translate-y-0">
                            </h1>
                            <p 
                                class="text-lg md:text-xl text-white"
                                x-text="slide.subtitle"
                                x-transition:enter="transition ease-out delay-500 duration-500"
                                x-transition:enter-start="opacity-0 transform translate-y-10"
                                x-transition:enter-end="opacity-100 transform translate-y-0">
                            </p>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Slider controls -->
            <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
                <template x-for="(slide, index) in heroSlides" :key="index">
                    <button 
                        @click="currentSlide = index" 
                        :class="{'bg-white': currentSlide === index, 'bg-gray-400': currentSlide !== index}"
                        class="w-3 h-3 rounded-full transition-colors duration-300 focus:outline-none">
                    </button>
                </template>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="container mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow-md p-6 mb-8 transition-all duration-300 transform hover:shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input 
                            type="text" 
                            x-model="searchQuery" 
                            @input="filterEvenements()"
                            placeholder="Rechercher un événement..." 
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <select 
                        x-model="selectedCategory" 
                        @change="filterEvenements()"
                        class="w-full md:w-auto px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Toutes les catégories</option>
                        <template x-for="categorie in categories" :key="categorie.id">
                            <option :value="categorie.id" x-text="categorie.nom"></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>

        <!-- Event Count and Sorting -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div class="text-gray-600 mb-4 md:mb-0">
                <span x-text="filteredEvenements.length"></span> événements trouvés
            </div>
            <div class="flex items-center">
                <label for="sort" class="mr-2 text-gray-600">Trier par:</label>
                <select 
                    id="sort" 
                    x-model="sortBy" 
                    @change="sortEvenements()"
                    class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="date_asc">Date (croissant)</option>
                    <option value="date_desc">Date (décroissant)</option>
                    <option value="titre_asc">Titre (A-Z)</option>
                    <option value="titre_desc">Titre (Z-A)</option>
                </select>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
            <template x-for="evenement in paginatedEvenements" :key="evenement.id">
                <div 
                    class="bg-white rounded-xl overflow-hidden shadow-md transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl"
                    x-data="{ currentImageIndex: 0 }">
                    <!-- Images slider -->
                    <div class="relative h-64 overflow-hidden bg-gray-200">
                        <template x-if="evenement.medias && evenement.medias.length > 0">
                            <template x-for="(media, index) in evenement.medias" :key="index">
                                <img 
                                    x-show="currentImageIndex === index"
                                    :src="media.url"
                                    :alt="evenement.titre"
                                    class="w-full h-full object-cover transition-opacity duration-500"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0">
                            </template>
                        </template>
                        <template x-if="!evenement.medias || evenement.medias.length === 0">
                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </template>

                        <!-- Image navigation -->
                        <template x-if="evenement.medias && evenement.medias.length > 1">
                            <div class="absolute inset-x-0 bottom-0 flex justify-center space-x-2 p-2">
                                <template x-for="(media, index) in evenement.medias" :key="index">
                                    <button 
                                        @click.prevent="currentImageIndex = index"
                                        :class="{'bg-white': currentImageIndex === index, 'bg-gray-400': currentImageIndex !== index}"
                                        class="w-2 h-2 rounded-full focus:outline-none transition-colors duration-300">
                                    </button>
                                </template>
                            </div>
                        </template>

                        <!-- Category badge -->
                        <div class="absolute top-4 right-4">
                            <span 
                                class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-600 text-white"
                                x-text="evenement.categorie ? evenement.categorie.nom : 'Sans catégorie'">
                            </span>
                        </div>

                        <!-- Image navigation arrows -->
                        <template x-if="evenement.medias && evenement.medias.length > 1">
                            <div class="absolute inset-y-0 left-0 flex items-center">
                                <button 
                                    @click.prevent="currentImageIndex = (currentImageIndex - 1 + evenement.medias.length) % evenement.medias.length"
                                    class="bg-black bg-opacity-50 text-white rounded-r-lg p-1 focus:outline-none hover:bg-opacity-75 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                            </div>
                        </template>

                        <template x-if="evenement.medias && evenement.medias.length > 1">
                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <button 
                                    @click.prevent="currentImageIndex = (currentImageIndex + 1) % evenement.medias.length"
                                    class="bg-black bg-opacity-50 text-white rounded-l-lg p-1 focus:outline-none hover:bg-opacity-75 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Event details -->
                    <div class="p-6">
                        <div class="mb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-600" x-text="formatDate(evenement.date_debut) + ' - ' + formatDate(evenement.date_fin)"></span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2" x-text="evenement.titre"></h3>
                        <div class="mb-3 flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-2 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-sm text-gray-600" x-text="evenement.lieu"></p>
                        </div>
                        <p class="text-gray-700 mb-4 line-clamp-3" x-text="evenement.description"></p>
                        <div class="flex space-x-3">
                            <a 
                                :href="`/evenements/${evenement.id}`"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg transition duration-300 ease-in-out flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Détails
                            </a>
                            <button 
                                @click="reserver(evenement.id)"
                                class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300 ease-in-out flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Réserver
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Pagination -->
        <div class="mt-10 text-center" x-show="filteredEvenements.length > itemsPerPage">
            <template x-if="totalPages > 1">
                <div class="flex flex-wrap justify-center gap-2">
                    <!-- Previous button -->
                    <button 
                        @click="changePage('prev')"
                        :disabled="currentPage === 1"
                        :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Page numbers -->
                    <template x-for="page in pagesArray" :key="page">
                        <button 
                            @click="goToPage(page)"
                            :class="{'bg-indigo-600 text-white': currentPage === page, 'bg-gray-200 text-gray-700': currentPage !== page}"
                            class="px-4 py-2 rounded-lg hover:bg-indigo-500 hover:text-white transition duration-200">
                            <span x-text="page"></span>
                        </button>
                    </template>

                    <!-- Next button -->
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

        <!-- No events message -->
        <div x-show="filteredEvenements.length === 0" class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900">Aucun événement trouvé</h3>
            <p class="mt-2 text-gray-600">Essayez de modifier vos critères de recherche.</p>
        </div>
    </div>
</div>

<script>
    function evenementsApp() {
        return {
            evenements: @json($evenements),
            categories: @json($categories),
            filteredEvenements: [],
            searchQuery: '',
            selectedCategory: '',
            sortBy: 'date_asc',
            currentPage: 1,
            itemsPerPage: 6,
            heroSlides: [
                {
                    image: '/images/events/banner1.jpg',
                    title: 'Découvrez nos événements culturels',
                    subtitle: 'Expositions, concerts, spectacles et bien plus encore'
                },
                {
                    image: '/images/events/banner2.png',
                    title: 'Vivez des expériences inoubliables',
                    subtitle: 'Des événements pour tous les goûts et tous les âges'
                },
                {
                    image: '/images/events/banner3.png',
                    title: 'Restez connectés à la culture',
                    subtitle: 'Rejoignez-nous lors de nos prochains événements'
                }
            ],
            currentSlide: 0,
            
            init() {
                this.filteredEvenements = this.evenements;
                this.startSlideshow();

            },
            
            startSlideshow() {
                setInterval(() => {
                    this.currentSlide = (this.currentSlide + 1) % this.heroSlides.length;
                }, 3000);
            },
            
            filterEvenements() {
                this.currentPage = 1;
                
                this.filteredEvenements = this.evenements.filter(event => {
                    const matchesSearch = event.titre.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                          event.description.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                          event.lieu.toLowerCase().includes(this.searchQuery.toLowerCase());
                    
                    const matchesCategory = this.selectedCategory === '' || 
                                           (event.categorie && event.categorie.id == this.selectedCategory);
                    
                    return matchesSearch && matchesCategory;
                });
                
                this.sortEvenements();
            },
            
            sortEvenements() {
                switch(this.sortBy) {
                    case 'date_asc':
                        this.filteredEvenements.sort((a, b) => new Date(a.date_debut) - new Date(b.date_debut));
                        break;
                    case 'date_desc':
                        this.filteredEvenements.sort((a, b) => new Date(b.date_debut) - new Date(a.date_debut));
                        break;
                    case 'titre_asc':
                        this.filteredEvenements.sort((a, b) => a.titre.localeCompare(b.titre));
                        break;
                    case 'titre_desc':
                        this.filteredEvenements.sort((a, b) => b.titre.localeCompare(a.titre));
                        break;
                }
            },
            
            formatDate(dateString) {
                const options = { day: 'numeric', month: 'short', year: 'numeric' };
                return new Date(dateString).toLocaleDateString('fr-FR', options);
            },
            
            get totalPages() {
                return Math.ceil(this.filteredEvenements.length / this.itemsPerPage);
            },
            
            get pagesArray() {
                const pages = [];
                for (let i = 1; i <= this.totalPages; i++) {
                    pages.push(i);
                }
                return pages;
            },
            
            get paginatedEvenements() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredEvenements.slice(start, end);
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
            
            reserver(evenementId) {
                window.location.href = `/reservation/${evenementId}`;
            }
        }
    }
</script>
@endsection