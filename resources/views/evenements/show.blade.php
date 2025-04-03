@extends('layouts.app')

@section('content')
<div x-data="eventData()" class="min-h-screen">
    <!-- Bannière / Slider avec première image -->
    <div class="relative h-80 md:h-96 overflow-hidden" x-ref="bannerContainer">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black opacity-60 z-10"></div>
        
        <div x-show="medias.length > 0" class="relative h-full w-full">
            <!-- Image principale -->
            <div class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                 :class="{'opacity-100': currentSlide === 0, 'opacity-0': currentSlide !== 0}"
                 :style="`background-image: url('${medias[0]?.url || ''}'); background-size: cover; background-position: center;`">
            </div>
            
            <!-- Images supplémentaires -->
            <template x-for="(media, index) in medias.slice(1)" :key="index">
                <div class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                     :class="{'opacity-100': currentSlide === index + 1, 'opacity-0': currentSlide !== index + 1}"
                     :style="`background-image: url('${media.url}'); background-size: cover; background-position: center;`">
                </div>
            </template>
            
            <!-- Contrôles du slider -->
            <div class="absolute bottom-4 left-0 right-0 z-20 flex justify-center gap-2">
                <template x-for="(media, index) in medias" :key="index">
                    <button @click="currentSlide = index" 
                            class="w-3 h-3 rounded-full transition-all duration-300"
                            :class="{'bg-white scale-110': currentSlide === index, 'bg-gray-400': currentSlide !== index}">
                    </button>
                </template>
            </div>
            
            <!-- Flèches de navigation -->
            <button @click="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-black/30 hover:bg-black/50 text-white rounded-full p-2 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button @click="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-black/30 hover:bg-black/50 text-white rounded-full p-2 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        
        <!-- Fallback si pas d'images -->
        <div x-show="medias.length === 0" class="h-full w-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        
        <!-- Titre de l'événement sur la bannière -->
        <div class="absolute bottom-0 left-0 right-0 z-20 px-6 py-8">
            <div class="max-w-6xl mx-auto">
                <span x-show="categorie" x-text="categorie.nom" 
                      class="inline-block px-3 py-1 text-xs font-medium tracking-wide text-white uppercase bg-indigo-600 rounded-full mb-2 animate-fade-in-up">
                </span>
                <h1 x-text="titre" 
                    class="text-3xl md:text-4xl lg:text-5xl font-bold text-white animate-fade-in-up" 
                    style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                </h1>
            </div>
        </div>
    </div>
    
    <!-- Contenu principal -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
        <!-- Carte info rapide -->
        <div class="bg-white rounded-lg shadow-xl p-6 mb-8 transform transition-all duration-500 hover:shadow-2xl"
             x-intersect:enter="opacity-0 translate-y-4"
             x-intersect:enter-start="opacity-0 translate-y-4"
             x-intersect:enter-end="opacity-100 translate-y-0"
             x-intersect:leave="opacity-100 translate-y-0"
             x-intersect:leave-start="opacity-100 translate-y-0"
             x-intersect:leave-end="opacity-0 translate-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Date -->
                <div class="flex items-start space-x-4">
                    <div class="bg-indigo-100 p-3 rounded-lg text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Date</h3>
                        <div class="mt-1">
                            <p class="text-base font-medium text-gray-900" x-text="formatDate(date_debut)"></p>
                            <p x-show="date_fin && date_fin !== date_debut" class="text-sm text-gray-600">
                                jusqu'au <span x-text="formatDate(date_fin)"></span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Lieu -->
                <div class="flex items-start space-x-4">
                    <div class="bg-red-100 p-3 rounded-lg text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Lieu</h3>
                        <p class="mt-1 text-base font-medium text-gray-900" x-text="lieu"></p>
                        <button @click="showMap = !showMap" class="mt-1 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                            <span x-text="showMap ? 'Masquer la carte' : 'Voir sur la carte'"></span>
                        </button>
                    </div>
                </div>
                
                <!-- Catégorie -->
                <div class="flex items-start space-x-4">
                    <div class="bg-amber-100 p-3 rounded-lg text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Catégorie</h3>
                        <p class="mt-1 text-base font-medium text-gray-900" x-text="categorie?.nom || 'Non catégorisé'"></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Carte si disponible -->
        <div x-show="showMap && latitude && longitude" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white rounded-lg shadow-lg overflow-hidden mb-8 h-64"
             x-init="initMap()">
            <div id="map" class="h-full w-full"></div>
        </div>
        
        <!-- Description -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8"
             x-intersect:enter="opacity-0 translate-y-4"
             x-intersect:enter-start="opacity-0 translate-y-4"
             x-intersect:enter-end="opacity-100 translate-y-0"
             x-intersect:leave="opacity-100 translate-y-0"
             x-intersect:leave-start="opacity-100 translate-y-0"
             x-intersect:leave-end="opacity-0 translate-y-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">À propos de cet événement</h2>
            <div x-html="formatDescription(description)" class="prose max-w-none text-gray-600 leading-relaxed"></div>
        </div>
        
        <!-- Galerie photos -->
        <div x-show="medias.length > 1" class="mb-8"
             x-intersect:enter="opacity-0 translate-y-4"
             x-intersect:enter-start="opacity-0 translate-y-4"
             x-intersect:enter-end="opacity-100 translate-y-0"
             x-intersect:leave="opacity-100 translate-y-0"
             x-intersect:leave-start="opacity-100 translate-y-0"
             x-intersect:leave-end="opacity-0 translate-y-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Galerie</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                <template x-for="(media, index) in medias" :key="index">
                    <div @click="openLightbox(index)" class="relative overflow-hidden rounded-lg aspect-square cursor-pointer group">
                        <img :src="media.url" :alt="'Image ' + (index + 1)" class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Lightbox -->
        <div x-show="lightboxOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="lightboxOpen = false"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4">
            <button @click="lightboxOpen = false" class="absolute top-4 right-4 text-white hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="relative w-full max-w-4xl">
                <div class="flex items-center justify-center min-h-64">
                    <template x-for="(media, index) in medias" :key="index">
                        <img x-show="lightboxIndex === index" 
                        :src="media.url" 
                             :alt="'Image ' + (index + 1)"
                             class="max-h-[80vh] max-w-full object-contain">
                    </template>
                </div>
                
                <!-- Navigation lightbox -->
                <div class="absolute top-1/2 -translate-y-1/2 left-0 right-0 flex justify-between">
                    <button @click="prevLightboxImage()" class="bg-black/30 hover:bg-black/50 text-white rounded-full p-2 transition-all duration-300 transform -translate-x-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click="nextLightboxImage()" class="bg-black/30 hover:bg-black/50 text-white rounded-full p-2 transition-all duration-300 transform translate-x-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                
                <!-- Indicateur numéro d'image -->
                <div class="absolute bottom-4 left-0 right-0 text-center text-white">
                    <span x-text="lightboxIndex + 1"></span> / <span x-text="medias.length"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts JS pour Alpine et la carte -->
@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<script>
    function eventData() {
        return {
            // Données de l'événement
            id: {{ $evenement->id }},
            titre: "{{ $evenement->titre }}",
            description: `{!! addslashes($evenement->description) !!}`,
            date_debut: "{{ $evenement->date_debut }}",
            date_fin: "{{ $evenement->date_fin }}",
            lieu: "{{ $evenement->lieu }}",
            latitude: {{ $evenement->latitude ?? 'null' }},
            longitude: {{ $evenement->longitude ?? 'null' }},
            categorie: @json($evenement->categorie),
            medias: @json($evenement->medias),
            
            // État du composant
            currentSlide: 0,
            showMap: false,
            lightboxOpen: false,
            lightboxIndex: 0,
            
            // Initialiser la carte
            initMap() {
                if (this.showMap && this.latitude && this.longitude) {
                    setTimeout(() => {
                        const map = L.map('map').setView([this.latitude, this.longitude], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);
                        
                        // Ajouter un marqueur à l'emplacement de l'événement
                        L.marker([this.latitude, this.longitude])
                            .addTo(map)
                            .bindPopup(this.lieu)
                            .openPopup();
                    }, 100);
                }
            },
            
            // Navigation du slider
            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.medias.length;
            },
            prevSlide() {
                this.currentSlide = this.currentSlide === 0 ? this.medias.length - 1 : this.currentSlide - 1;
            },
            
            // Lightbox - ouvrir avec l'image sélectionnée
            openLightbox(index) {
                this.lightboxIndex = index;
                this.lightboxOpen = true;
                document.body.classList.add('overflow-hidden');
            },
            
            // Navigation lightbox
            nextLightboxImage() {
                this.lightboxIndex = (this.lightboxIndex + 1) % this.medias.length;
            },
            prevLightboxImage() {
                this.lightboxIndex = this.lightboxIndex === 0 ? this.medias.length - 1 : this.lightboxIndex - 1;
            },
            
            // Format de la date pour l'affichage
            formatDate(dateString) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('fr-FR', options);
            },
            
            // Formatage du texte de la description (remplacement des sauts de ligne, etc.)
            formatDescription(text) {
                if (!text) return '';
                
                // Convertir les URLs en liens cliquables
                const urlRegex = /(https?:\/\/[^\s]+)/g;
                text = text.replace(urlRegex, '<a href="$1" target="_blank" class="text-indigo-600 hover:text-indigo-800 transition-colors">$1</a>');
                
                // Convertir les sauts de ligne en paragraphes
                text = '<p>' + text.replace(/\n\n/g, '</p><p>').replace(/\n/g, '<br>') + '</p>';
                
                return text;
            },
            
            // Initialisation du slider automatique
            init() {
                // Auto-rotation du slider toutes les 5 secondes si plus d'une image
                if (this.medias.length > 1) {
                    this.slideInterval = setInterval(() => {
                        this.nextSlide();
                    }, 5000);
                }
                
                // Arrêter l'intervalle quand la page est cachée
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        clearInterval(this.slideInterval);
                    } else if (this.medias.length > 1) {
                        this.slideInterval = setInterval(() => {
                            this.nextSlide();
                        }, 5000);
                    }
                });
                
                // Nettoyage lors de la destruction du composant
                this.$cleanup = () => {
                    clearInterval(this.slideInterval);
                    if (this.lightboxOpen) {
                        document.body.classList.remove('overflow-hidden');
                    }
                };
            }
        }
    }
</script>

<style>
    /* Animations supplémentaires */
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.5s ease-out forwards;
    }
</style>
@endpush
@endsection