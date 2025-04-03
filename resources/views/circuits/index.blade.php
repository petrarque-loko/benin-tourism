@extends('layouts.app')

@section('content')

@section('title', 'Nos Circuits Touristiques')

<!-- Styles pour animations et design -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideInUp {
        from { transform: translateY(50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.6s ease forwards;
    }
    
    .animate-slide-up {
        animation: slideInUp 0.5s ease forwards;
    }

    .slider-bg {
        background-size: cover;
        background-position: center;
        transition: opacity 1s ease-in-out;
    }
    
    .image-slider {
        transition: transform 0.3s ease;
    }
    
    .image-slider:hover {
        transform: scale(1.03);
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
</style>

<div class="bg-gray-50">
    <!-- Hero Slider -->
    <div x-data="{
        heroSlides: [
            {
                image: '/images/bg1.jpeg',
                title: 'Découvrez des Paysages à Couper le Souffle',
                subtitle: 'Explorez nos circuits touristiques uniques'
            },
            {
                image: '/images/bg2.jpeg',
                title: 'Vivez des Expériences Inoubliables',
                subtitle: 'Avec nos guides experts et passionnés'
            },
            {
                image: '/images/bg3.jpeg',
                title: 'Créez des Souvenirs Mémorables',
                subtitle: 'Parcourez nos destinations exclusives'
            }
        ],
        currentSlide: 0,
        autoplay: null,
        
        init() {
            this.autoplay = setInterval(() => {
                this.currentSlide = (this.currentSlide + 1) % this.heroSlides.length;
            }, 5000);
        },
        
        stopAutoplay() {
            clearInterval(this.autoplay);
        },
        
        restartAutoplay() {
            this.stopAutoplay();
            this.autoplay = setInterval(() => {
                this.currentSlide = (this.currentSlide + 1) % this.heroSlides.length;
            }, 5000);
        },
        
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.heroSlides.length;
            this.restartAutoplay();
        },
        
        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.heroSlides.length) % this.heroSlides.length;
            this.restartAutoplay();
        }
    }"
    x-init="init()"
    class="relative h-screen overflow-hidden">
        <!-- Background Slides -->
        <template x-for="(slide, index) in heroSlides" :key="index">
            <div 
                class="absolute inset-0 slider-bg w-full h-full" 
                :style="`background-image: url('${slide.image}'); opacity: ${currentSlide === index ? 1 : 0}`">
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            </div>
        </template>
        
        <!-- Slide Content -->
        <div class="relative z-10 flex items-center justify-center h-full text-white text-center px-4">
            <div>
                <h1 
                    x-text="heroSlides[currentSlide].title" 
                    class="text-4xl md:text-6xl font-bold mb-4 animate-fade-in"
                    style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                </h1>
                <p 
                    x-text="heroSlides[currentSlide].subtitle" 
                    class="text-xl md:text-2xl mb-8 animate-slide-up"
                    style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                </p>
                <a href="#circuits" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-8 rounded-full transition-all duration-5000 transform hover:scale-105 animate-slide-up">
                    <i class="fas fa-compass mr-2"></i>Explorer nos circuits
                </a>
            </div>
        </div>
        
        <!-- Navigation Arrows -->
        <button @click="prevSlide" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-4xl hover:text-yellow-5000 transition-colors z-20">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button @click="nextSlide" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-4xl hover:text-yellow-5000 transition-colors z-20">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Navigation Dots -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
            <template x-for="(slide, index) in heroSlides" :key="index">
                <button 
                    @click="currentSlide = index; restartAutoplay()" 
                    :class="`w-3 h-3 rounded-full transition-all duration-5000 ${currentSlide === index ? 'bg-yellow-500 w-8' : 'bg-white bg-opacity-50'}`">
                </button>
            </template>
        </div>
        
        <!-- Scroll Down Indicator -->
        <div class="absolute bottom-5 left-1/2 transform -translate-x-1/2 animate-bounce z-20">
            <a href="#circuits" class="text-white opacity-80 hover:opacity-100">
                <i class="fas fa-chevron-down text-2xl"></i>
            </a>
        </div>
    </div>

    <!-- Search Section -->
    <div id="circuits" class="py-12">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Trouvez Votre Circuit Idéal</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Filtrez parmi nos circuits touristiques exceptionnels pour trouver celui qui correspond parfaitement à vos attentes.</p>
            </div>
            
            <div x-data="{
                filters: {
                    difficulte: '',
                    prix_min: 0,
                    prix_max: 300,
                    duree_max: '',
                    site_touristique: ''
                },
                circuits: {{ Illuminate\Support\Js::from($circuits) }},
                initialCircuits: {{ Illuminate\Support\Js::from($circuits) }},
                loading: false,
                prixRange: 300,
                sitesTouristiques: [],
                
                init() {
                    const sites = [];
                    this.circuits.forEach(circuit => {
                        circuit.sites_touristiques.forEach(site => {
                            if (!sites.some(s => s.id === site.id)) {
                                sites.push({
                                    id: site.id,
                                    nom: site.nom
                                });
                            }
                        });
                    });
                    this.sitesTouristiques = sites;
                    
                    // Ajouter des watchers pour appliquer les filtres automatiquement
                    this.$watch('filters.difficulte', () => this.applyFilters());
                    this.$watch('filters.prix_max', () => this.applyFilters());
                    this.$watch('filters.duree_max', () => this.applyFilters());
                    this.$watch('filters.site_touristique', () => this.applyFilters());
                },
                
                async applyFilters() {
                    this.loading = true;
                    try {
                        const response = await fetch('/circuits/search-ajax?' + new URLSearchParams({
                            difficulte: this.filters.difficulte,
                            prix_min: this.filters.prix_min,
                            prix_max: this.filters.prix_max,
                            duree_max: this.filters.duree_max,
                            site_touristique: this.filters.site_touristique
                        }));
                        if (response.ok) {
                            const data = await response.json();
                            console.log('Données reçues:', data); // Débogage
                            this.circuits = data;
                        } else {
                            console.error('Erreur lors de la recherche:', response.status);
                        }
                    } catch (error) {
                        console.error('Erreur AJAX:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                resetFilters() {
                    this.filters = {
                        difficulte: '',
                        prix_min: 0,
                        prix_max: 300,
                        duree_max: '',
                        site_touristique: ''
                    };
                    this.prixRange = 300;
                    this.circuits = this.initialCircuits;
                }
            }"
            x-init="init()"
            class="mb-12">
                <!-- Filtres de recherche -->
                <div class="bg-gray-100 rounded-xl p-6 shadow-md mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Difficulté -->
                        <div>
                            <label for="difficulte" class="block text-sm font-medium text-gray-700 mb-1">Niveau de difficulté</label>
                            <select 
                                id="difficulte" 
                                x-model="filters.difficulte" 
                                class="w-full border-gray-5000 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                            >
                                <option value="">Tous les niveaux</option>
                                <option value="facile">Facile</option>
                                <option value="moyen">Moyen</option>
                                <option value="difficile">Difficile</option>
                            </select>
                        </div>
                        
                        <!-- Prix -->
                        <div> 
                            <label for="prix" class="block text-sm font-medium text-gray-700 mb-1">Prix maximum: <span x-text="filters.prix_max + ' €'"></span></label>
                            <input 
                                id="prix" 
                                type="range" 
                                min="0" 
                                max="300" 
                                step="10" 
                                x-model="filters.prix_max" 
                                class="w-full h-2 bg-yellow-200 rounded-lg appearance-none cursor-pointer"
                            >
                        </div>
                        
                        <!-- Durée -->
                        <div>
                            <label for="duree" class="block text-sm font-medium text-gray-700 mb-1">Durée maximum (jours)</label>
                            <select 
                                id="duree" 
                                x-model="filters.duree_max" 
                                class="w-full border-gray-5000 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                            >
                                <option value="">Toutes les durées</option>
                                <option value="2">2 jours</option>
                                <option value="4">4 jours</option>
                                <option value="6">6 jours</option>
                                <option value="8">8 jours</option>
                                <option value="12">12 jours</option>
                            </select>
                        </div>
                        
                        <!-- Site touristique -->
                        <div>
                            <label for="site" class="block text-sm font-medium text-gray-700 mb-1">Site touristique</label>
                            <select 
                                id="site" 
                                x-model="filters.site_touristique" 
                                class="w-full border-gray-5000 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                            >
                                <option value="">Tous les sites</option>
                                <template x-for="site in sitesTouristiques" :key="site.id">
                                    <option :value="site.id" x-text="site.nom"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-center space-x-4">
                        <!-- Bouton de recherche - maintenant caché car filtrage automatique -->
                        <button 
                            @click="applyFilters()" 
                            class="hidden bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-6 rounded-lg transition-all duration-5000 flex items-center"
                            :class="{'opacity-70 cursor-wait': loading}"
                            :disabled="loading"
                        >
                            <i class="fas fa-search mr-2"></i>
                            <span x-text="loading ? 'Recherche en cours...' : 'Rechercher'"></span>
                        </button>
                        <button 
                            @click="resetFilters()" 
                            class="bg-gray-200 hover:bg-gray-5000 text-gray-700 py-2 px-6 rounded-lg transition-all duration-5000 flex items-center"
                        >
                            <i class="fas fa-undo mr-2"></i>
                            Réinitialiser
                        </button>
                    </div>
                </div>
                
                <!-- Indicateur de chargement flottant -->
                <div 
                    x-show="loading" 
                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white bg-opacity-90 rounded-lg p-4 shadow-lg z-50 flex items-center space-x-3"
                >
                    <div class="animate-spin rounded-full h-6 w-6 border-t-2 border-b-2 border-yellow-500"></div>
                    <span class="text-yellow-800 font-medium">Recherche en cours...</span>
                </div>
                
                <!-- Résultats des circuits -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-route mr-3 text-yellow-500"></i>
                        Nos Circuits
                        <span class="ml-2 text-lg text-gray-500" x-text="`(${circuits.length} résultats)`"></span>
                    </h3>
                    
                    <!-- Aucun résultat -->
                    <div x-show="!loading && circuits.length === 0" class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                        <i class="fas fa-exclamation-circle text-yellow-500 text-4xl mb-4"></i>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Aucun circuit trouvé</h4>
                        <p class="text-gray-600">Veuillez modifier vos critères de recherche et réessayer.</p>
                    </div>
                    
                    <!-- Cartes des circuits -->
                    <div 
                        x-show="!loading && circuits.length > 0"
                        class="grid grid-cols-1 md:grid-cols-2 gap-8"
                    >
                        <template x-for="(circuit, index) in circuits" :key="circuit.id">
                            <div 
                                class="bg-white rounded-xl overflow-hidden shadow-lg card-hover transition-all duration-5000 transform"
                                x-data="{
                                    currentImage: 0,
                                    autoplayInterval: null,
                                    
                                    init() {
                                        if (circuit.images_sites && circuit.images_sites.length) {
                                            this.startAutoplay();
                                        }
                                    },
                                    
                                    startAutoplay() {
                                        this.autoplayInterval = setInterval(() => {
                                            this.currentImage = (this.currentImage + 1) % circuit.images_sites.length;
                                        }, 50000);
                                    },
                                    
                                    stopAutoplay() {
                                        clearInterval(this.autoplayInterval);
                                    },
                                    
                                    prevImage() {
                                        this.stopAutoplay();
                                        this.currentImage = (this.currentImage - 1 + circuit.images_sites.length) % circuit.images_sites.length;
                                        this.startAutoplay();
                                    },
                                    
                                    nextImage() {
                                        this.stopAutoplay();
                                        this.currentImage = (this.currentImage + 1) % circuit.images_sites.length;
                                        this.startAutoplay();
                                    }
                                }"
                                x-init="init()"
                                @mouseleave.away="startAutoplay()"
                            >
                                <!-- Slider d'images -->
                                <div class="relative h-64 overflow-hidden group">
                                    <template x-if="circuit.images_sites && circuit.images_sites.length > 0">
                                        <template x-for="(site, siteIndex) in circuit.images_sites" :key="siteIndex">
                                            <div 
                                                class="absolute inset-0 transition-opacity duration-500 bg-cover bg-center"
                                                :class="currentImage === siteIndex ? 'opacity-100' : 'opacity-0'"
                                                :style="`background-image: url('${site.media_url}')`"
                                            ></div>
                                        </template>
                                    </template>
                                    
                                    <template x-if="!circuit.images_sites || circuit.images_sites.length === 0">
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-5xl"></i>
                                        </div>
                                    </template>
                                    
                                    <!-- Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black opacity-60"></div>
                                    
                                    <!-- Contrôles de navigation (visibles au survol) -->
                                    <template x-if="circuit.images_sites && circuit.images_sites.length > 1">
                                        <div class="absolute inset-0 flex items-center justify-between opacity-0 group-hover:opacity-100 transition-opacity px-2">
                                            <button 
                                                @click.stop="prevImage()" 
                                                class="bg-black bg-opacity-50 hover:bg-opacity-70 text-white w-8 h-8 rounded-full flex items-center justify-center transition-all"
                                            >
                                                <i class="fas fa-chevron-left"></i>
                                            </button>
                                            <button 
                                                @click.stop="nextImage()" 
                                                class="bg-black bg-opacity-50 hover:bg-opacity-70 text-white w-8 h-8 rounded-full flex items-center justify-center transition-all"
                                            >
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </div>
                                    </template>
                                    
                                    <!-- Indicateurs de pagination -->
                                    <template x-if="circuit.images_sites && circuit.images_sites.length > 1">
                                        <div class="absolute bottom-2 left-0 right-0 flex justify-center space-x-1">
                                            <template x-for="(_, dotIndex) in circuit.images_sites" :key="dotIndex">
                                                <div 
                                                    @click.stop="currentImage = dotIndex; stopAutoplay(); startAutoplay();"
                                                    :class="`w-2 h-2 rounded-full cursor-pointer transition-all ${currentImage === dotIndex ? 'bg-yellow-500 w-4' : 'bg-white bg-opacity-50'}`"
                                                ></div>
                                            </template>
                                        </div>
                                    </template>
                                    
                                    <!-- Nom du circuit -->
                                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                        <h3 class="text-xl font-bold" x-text="circuit.nom"></h3>
                                    </div>
                                </div>
                                
                                <!-- Informations du circuit -->
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex items-center">
                                            <div class="bg-yellow-100 text-yellow-800 rounded-full px-3 py-1 text-sm font-medium">
                                                <i class="fas fa-hiking mr-1"></i>
                                                <span class="capitalize" x-text="circuit.difficulte || 'Non spécifié'"></span>
                                            </div>
                                            <div class="ml-2 flex items-center text-yellow-500">
                                                <i class="fas fa-star"></i>
                                                <span class="ml-1 text-gray-800" x-text="circuit.note_moyenne ? parseFloat(circuit.note_moyenne).toFixed(1) : 'N/A'"></span>
                                            </div>
                                        </div>
                                        <div class="text-lg font-bold text-gray-800">
                                            <span x-text="circuit.prix"></span> €
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-clock text-gray-500 mr-2"></i>
                                            <span class="text-gray-700" x-text="`Durée: ${circuit.duree} jours`"></span>
                                        </div>
                                        <div class="flex items-center" x-show="circuit.guide">
                                            <i class="fas fa-user-tie text-gray-500 mr-2"></i>
                                            <span class="text-gray-700" x-text="`Guide: ${circuit.guide ? circuit.guide.nom + ' ' + circuit.guide.prenom : 'Non assigné'}`"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <p class="text-gray-600 line-clamp-2" x-text="circuit.description"></p>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2 mb-4">
                                        <div class="text-sm text-gray-500">Sites inclus:</div>
                                        <div class="flex flex-wrap gap-1">
                                            <template x-for="(site, i) in circuit.sites_touristiques.slice(0, 3)" :key="site.id">
                                                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded" x-text="site.nom"></span>
                                            </template>
                                            <template x-if="circuit.sites_touristiques.length > 3">
                                                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded" x-text="`+${circuit.sites_touristiques.length - 3}`"></span>
                                            </template>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-3">
                                        <a 
                                            :href="`/circuits/${circuit.id}`" 
                                            class="flex-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-center py-2 rounded-lg transition-all font-medium text-sm flex items-center justify-center"
                                        >
                                            <i class="fas fa-eye mr-2"></i>
                                            Voir les détails
                                        </a>
                                        <a 
                                            :href="`/reservations/circuits/create/${circuit.id}`" 
                                            class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 rounded-lg transition-all font-medium text-sm flex items-center justify-center"
                                        >
                                            <i class="fas fa-calendar-check mr-2"></i>
                                            Réserver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Call to Action -->
    <div class="py-16 bg-yellow-50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Prêt à Vivre une Aventure Inoubliable?</h2>
            <p class="text-gray-600 max-w-xl mx-auto mb-8">Rejoignez-nous pour découvrir des paysages magnifiques et des expériences culturelles enrichissantes avec nos guides experts.</p>
            <div class="flex flex-col md:flex-row justify-center space-y-4 md:space-y-0 md:space-x-4">
                <a href="/circuits" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-8 rounded-lg transition-all duration-5000 transform hover:scale-105 flex items-center justify-center">
                    <i class="fas fa-compass mr-2"></i>
                    Explorer tous nos circuits
                </a>
                <a href="/contact" class="bg-white hover:bg-gray-100 text-gray-800 font-bold py-3 px-8 rounded-lg transition-all duration-5000 border border-gray-5000 flex items-center justify-center">
                    <i class="fas fa-question-circle mr-2"></i>
                    Besoin d'aide?
                </a>
            </div>
        </div>
    </div>
    
    <!-- Scripts pour animations au défilement -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.card-hover').forEach(card => {
                observer.observe(card);
            });
        });
    </script>
</div>

@endsection   