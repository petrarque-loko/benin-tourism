@extends('layouts.app')

@section('title', 'Découvrez le Bénin | Votre prochaine destination touristique en Afrique de l\'Ouest')

@section('meta')
    <meta name="description" content="Explorez les merveilles du Bénin : sites UNESCO, parcs nationaux, traditions uniques, événements culturels et hébergements confortables. Planifiez votre voyage dès aujourd'hui !">
    <meta name="keywords" content="tourisme Bénin, sites touristiques, circuits Bénin, traditions béninoises, événements Bénin, hébergements Bénin, voyage Afrique, culture vaudou">
@endsection

@push('styles')
<style>
    /* Animation keyframes */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .animate-pulse-slow {
        animation: pulse 3s infinite;
    }

    /* Parallax effect */
    .parallax-bg {
        background-attachment: fixed;
    }
    
    /* Custom transitions */
    .custom-transition {
        transition: all 0.5s cubic-bezier(0.25, 0.1, 0.25, 1);
    }
    
    /* Image carousel styles */
    .carousel-item {
        transition: opacity 0.5s ease-in-out;
    }
    
    /* Enhanced hover effects */
    .hover-zoom:hover img {
        transform: scale(1.08);
        filter: brightness(1.1);
    }
    
    .hero-gradient-overlay {
        background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.6) 100%);
    }
</style>
@endpush

@section('content')
    <!-- Hero Section with Video Background -->
    <section 
        x-data="{ 
            activeSlide: 0,
            slides: [
                '{{ asset('images/bg1.jpeg') }}',
                '{{ asset('images/bg2.jpeg') }}',
                '{{ asset('images/bg3.jpeg') }}',
                '{{ asset('images/bg4.jpeg') }}'
            ],
            nextSlide() {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            },
            prevSlide() {
                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
            }
        }" 
        class="relative min-h-screen flex items-center justify-center text-white overflow-hidden">
        
        <!-- Slider Images -->
        <div class="absolute inset-0 z-0">
            <template x-for="(slide, index) in slides" :key="index">
                <div 
                    class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out"
                    :style="`background-image: url('${slide}')`"
                    :class="{ 'opacity-100': activeSlide === index, 'opacity-0': activeSlide !== index }">
                </div>
            </template>
        </div>
        
        <!-- Contenu de la bannière -->
        <div class="max-w-6xl mx-auto px-4 z-10 text-center">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight tracking-tighter">
                <span class="block text-white">Découvrez les</span>
                <span class="block text-yellow-600">Merveilles du Bénin</span>
            </h1>
            <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto font-light text-white">
                Voyagez au cœur de l'Afrique authentique : paysages époustouflants, culture vibrante et hospitalité légendaire
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-5">
                <a href="{{ route('sites.index') }}" class="group inline-flex items-center justify-center rounded-full bg-yellow-500 hover:bg-yellow-600 px-8 py-4 font-semibold text-white transition duration-300 hover:scale-105">
                    <span class="flex items-center">
                        Explorer nos destinations
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </span>
                </a>
                <a href="{{ route('circuits.index') }}" class="group inline-flex items-center justify-center rounded-full bg-transparent hover:bg-white/20 px-8 py-4 font-semibold text-white border-2 border-white transition duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        Découvrir nos circuits
                    </span>
                </a>
            </div>
        </div>
        
        <!-- Contrôles du slider -->
        <div class="absolute bottom-10 left-0 right-0 flex justify-center space-x-3 z-10">
            <template x-for="(slide, index) in slides" :key="index">
                <button 
                    @click="activeSlide = index" 
                    :class="{ 'bg-white': activeSlide === index, 'bg-white/30': activeSlide !== index }"
                    class="w-3 h-3 rounded-full transition-colors duration-300 focus:outline-none">
                </button>
            </template>
        </div>
        
        <!-- Flèches de navigation -->
        <div class="absolute top-1/2 left-4 transform -translate-y-1/2 z-10">
            <button @click="prevSlide" class="bg-white/30 hover:bg-white/50 rounded-full p-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>
        <div class="absolute top-1/2 right-4 transform -translate-y-1/2 z-10">
            <button @click="nextSlide" class="bg-white/30 hover:bg-white/50 rounded-full p-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        
        <!-- Défilement automatique toutes les 3 secondes -->
        <div x-init="setInterval(() => { nextSlide() }, 3000)"></div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="py-10" x-data="{ shown: false }" x-intersect="shown = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 bg-gray-50">
                <span class="text-green-700 font-semibold text-sm uppercase tracking-wider">Pourquoi nous choisir</span>
                <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Une expérience authentique du Bénin</h2>
                <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Explorez les trésors du Bénin avec des circuits personnalisés, des guides locaux et des hébergements uniques.</p>
            </div>
        </div>
    </section>
    
    <!-- Section Sites Touristiques -->
    <section class="">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 bg-gray-50">
                <span class="text-green-700 font-semibold text-sm uppercase tracking-wider">Explorez</span>
                <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Sites Touristiques Populaires</h2>
                <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Découvrez les sites touristiques les plus emblématiques du Bénin, riches en histoire et beauté naturelle.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                @foreach ($sites as $site)
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover-zoom group">
                        <div class="relative h-60">
                            @if ($site->medias->isNotEmpty())
                                <div x-data="{ activeMedia: 0 }" class="carousel h-full">
                                    <template x-for="(media, index) in {{ $site->medias->toJson() }}" :key="index">
                                        <div 
                                            class="absolute inset-0 transition-opacity duration-500 carousel-item"
                                            :class="{ 'opacity-100': activeMedia === index, 'opacity-0': activeMedia !== index }">
                                            <img
                                                :src="'{{ asset('storage') }}/' + media.url" 
                                                alt="Photo de {{ $site->nom }}" 
                                                class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                    <!-- Défilement automatique toutes les 4 secondes -->
                                    <div x-init="setInterval(() => { activeMedia = (activeMedia + 1) % {{ $site->medias->count() }} }, 4000)"></div>
                                    <!-- Contrôles manuels -->
                                    <button @click="activeMedia = (activeMedia - 1 + {{ $site->medias->count() }}) % {{ $site->medias->count() }}" class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-white/50 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button @click="activeMedia = (activeMedia + 1) % {{ $site->medias->count() }}" class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-white/50 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <img src="{{ asset('images/default-site.jpg') }}" alt="Image par défaut de {{ $site->nom }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">{{ $site->nom }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($site->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-yellow-500 font-semibold">{{ $site->commentaires_avg_note ? number_format($site->commentaires_avg_note, 1) : 'Non noté' }}/5</span>
                                <a href="{{ route('sites.show', $site->id) }}" class="text-green-700 font-medium hover:text-green-800 transition flex items-center">
                                    Découvrir
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Section Circuits Touristiques -->
    <section class="py-20 ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 bg-gray-50">
                <span class="text-green-700 font-semibold text-sm uppercase tracking-wider">Parcourez</span>
                <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Circuits Touristiques Recommandés</h2>
                <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Explorez nos circuits pour une aventure immersive au Bénin.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                @foreach ($circuits as $circuit)
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover-zoom group">
                        <div class="relative h-60">
                            @if ($circuit->images_sites->isNotEmpty())
                                <div x-data="{ activeMedia: 0 }" class="carousel h-full">
                                    <template x-for="(media, index) in {{ $circuit->images_sites->toJson() }}" :key="index">
                                        <div 
                                            class="absolute inset-0 transition-opacity duration-500 carousel-item"
                                            :class="{ 'opacity-100': activeMedia === index, 'opacity-0': activeMedia !== index }">
                                            <img :src="media.media_url" :alt="'Photo de ' + media.site_nom" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                    <div x-init="setInterval(() => { activeMedia = (activeMedia + 1) % {{ $circuit->images_sites->count() }} }, 4000)"></div>
                                    <button @click="activeMedia = (activeMedia - 1 + {{ $circuit->images_sites->count() }}) % {{ $circuit->images_sites->count() }}" class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-white/50 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button @click="activeMedia = (activeMedia + 1) % {{ $circuit->images_sites->count() }}" class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-white/50 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <img src="{{ asset('images/default-circuit.jpg') }}" alt="Image par défaut de {{ $circuit->nom }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">{{ $circuit->nom }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($circuit->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-yellow-500 font-semibold">{{ $circuit->commentaires_avg_note ? number_format($circuit->commentaires_avg_note, 1) : 'Non noté' }}/5</span>
                                <a href="{{ route('circuits.show', $circuit->id) }}" class="text-green-700 font-medium hover:text-green-800 transition flex items-center">
                                    Découvrir
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Section Traditions et Coutumes -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 bg-gray-50">
                <span class="text-green-700 font-semibold text-sm uppercase tracking-wider">Culture</span>
                <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Traditions et Coutumes du Bénin</h2>
                <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Plongez dans la richesse culturelle du Bénin avec ses traditions uniques.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                @foreach ($traditions as $tradition)
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover-zoom group">
                        <div class="relative h-60">
                            @if ($tradition->medias->isNotEmpty())
                                <div x-data="{ activeMedia: 0 }" class="carousel h-full">
                                    <template x-for="(media, index) in {{ $tradition->medias->toJson() }}" :key="index">
                                        <div 
                                            class="absolute inset-0 transition-opacity duration-500 carousel-item"
                                            :class="{ 'opacity-100': activeMedia === index, 'opacity-0': activeMedia !== index }">
                                            <img :src="media.url" alt="Photo de {{ $tradition->nom }}" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                    <div x-init="setInterval(() => { activeMedia = (activeMedia + 1) % {{ $tradition->medias->count() }} }, 4000)"></div>
                                    <button @click="activeMedia = (activeMedia - 1 + {{ $tradition->medias->count() }}) % {{ $tradition->medias->count() }}" class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-white/50 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button @click="activeMedia = (activeMedia + 1) % {{ $tradition->medias->count() }}" class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-white/50 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <img src="{{ asset('images/default-tradition.jpg') }}" alt="Image par défaut de {{ $tradition->nom }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">{{ $tradition->nom }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($tradition->resume, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-yellow-500 font-semibold">{{ $tradition->commentaires_avg_note ? number_format($tradition->commentaires_avg_note, 1) : 'Non noté' }}/5</span>
                                <a href="{{ route('traditions_coutumes.show', $tradition->id) }}" class="text-green-700 font-medium hover:text-green-800 transition flex items-center">
                                    Découvrir
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Section Événements à venir -->
    <section class="py-20 ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 bg-gray-50">
                <span class="text-green-700 font-semibold text-sm uppercase tracking-wider">À ne pas manquer</span>
                <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Événements à venir au Bénin</h2>
                <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Participez aux événements culturels et festifs les plus attendus du Bénin.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                @foreach ($evenements as $evenement)
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover-zoom group">
                        <div class="relative h-60">
                            @if ($evenement->medias->isNotEmpty())
                                <div x-data="{ activeMedia: 0 }" class="carousel h-full">
                                    <template x-for="(media, index) in {{ $evenement->medias->toJson() }}" :key="index">
                                        <div 
                                            class="absolute inset-0 transition-opacity duration-500 carousel-item"
                                            :class="{ 'opacity-100': activeMedia === index, 'opacity-0': activeMedia !== index }">
                                            <img :src="media.url" alt="Photo de {{ $evenement->nom }}" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                    <div x-init="setInterval(() => { activeMedia = (activeMedia + 1) % {{ $evenement->medias->count() }} }, 4000)"></div>
                                    <button @click="activeMedia = (activeMedia - 1 + {{ $evenement->medias->count() }}) % {{ $evenement->medias->count() }}" class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-white/50 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button @click="activeMedia = (activeMedia + 1) % {{ $evenement->medias->count() }}" class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-white/50 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <img src="{{ asset('images/default-evenement.jpg') }}" alt="Image par défaut de {{ $evenement->nom }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">{{ $evenement->nom }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($evenement->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-yellow-500 font-semibold">{{ $evenement->commentaires_avg_note ? number_format($evenement->commentaires_avg_note, 1) : 'Non noté' }}/5</span>
                                <a href="{{ route('evenements.show', $evenement->id) }}" class="text-green-700 font-medium hover:text-green-800 transition flex items-center">
                                    Découvrir
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Section Chambres -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 bg-gray-50">
                <span class="text-green-700 font-semibold text-sm uppercase tracking-wider">Séjournez</span>
                <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Hébergements Recommandés</h2>
                <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Trouvez l'hébergement idéal pour votre séjour au Bénin.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                @foreach ($chambres as $chambre)
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover-zoom group">
                        <div class="relative h-60">
                            @if ($chambre->medias->isNotEmpty())
                                <div x-data="{ activeMedia: 0 }" class="carousel h-full">
                                    <template x-for="(media, index) in {{ $chambre->medias->toJson() }}" :key="index">
                                        <div 
                                            class="absolute inset-0 transition-opacity duration-500 carousel-item"
                                            :class="{ 'opacity-100': activeMedia === index, 'opacity-0': activeMedia !== index }">
                                            <img :src="'{{ asset('storage') }}/' + media.url" alt="Photo de {{ $chambre->nom }}" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                    <div x-init="setInterval(() => { activeMedia = (activeMedia + 1) % {{ $chambre->medias->count() }} }, 4000)"></div>
                                    <button @click="activeMedia = (activeMedia - 1 + {{ $chambre->medias->count() }}) % {{ $chambre->medias->count() }}" class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-white/50 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button @click="activeMedia = (activeMedia + 1) % {{ $chambre->medias->count() }}" class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-white/50 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <img src="{{ asset('images/default-chambre.jpg') }}" alt="Image par défaut de {{ $chambre->nom }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">{{ $chambre->nom }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($chambre->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-yellow-500 font-semibold">{{ $chambre->commentaires_avg_note ? number_format($chambre->commentaires_avg_note, 1) : 'Non noté' }}/5</span>
                                <a href="{{ route('chambres.show', $chambre->id) }}" class="text-green-700 font-medium hover:text-green-800 transition flex items-center">
                                    Découvrir
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Section Témoignages -->
    <section class="py-20 " x-data="{ activeTestimonialType: 'sites' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 bg-gray-50 bg-gray-50">
                <span class="text-green-700 font-semibold text-sm uppercase tracking-wider">Témoignages</span>
                <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Ce que disent nos voyageurs</h2>
                <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Lisez les avis de nos clients sur leurs expériences au Bénin.</p>
            </div>
            
            <!-- Tabs for Testimonial Types -->
            <div class="flex flex-wrap justify-center space-x-2 md:space-x-4 mb-12">
                <button 
                    @click="activeTestimonialType = 'sites'" 
                    :class="{ 'bg-green-700 text-white ring-2 ring-green-500 ring-offset-2': activeTestimonialType === 'sites', 'bg-white text-gray-700 hover:bg-gray-100': activeTestimonialType !== 'sites' }"
                    class="px-6 py-2 rounded-full font-medium transition duration-300 shadow-sm mb-2 md:mb-0">
                    Sites Touristiques
                </button>
                <button 
                    @click="activeTestimonialType = 'hebergements'" 
                    :class="{ 'bg-green-700 text-white ring-2 ring-green-500 ring-offset-2': activeTestimonialType === 'hebergements', 'bg-white text-gray-700 hover:bg-gray-100': activeTestimonialType !== 'hebergements' }"
                    class="px-6 py-2 rounded-full font-medium transition duration-300 shadow-sm mb-2 md:mb-0">
                    Hébergements
                </button>
                <button 
                    @click="activeTestimonialType = 'circuits'" 
                    :class="{ 'bg-green-700 text-white ring-2 ring-green-500 ring-offset-2': activeTestimonialType === 'circuits', 'bg-white text-gray-700 hover:bg-gray-100': activeTestimonialType !== 'circuits' }"
                    class="px-6 py-2 rounded-full font-medium transition duration-300 shadow-sm mb-2 md:mb-0">
                    Circuits
                </button>
            </div>
            
            <!-- Testimonials Content -->
            <div class="relative mx-auto max-w-4xl">
                <!-- Sites Testimonials -->
                <div 
                    x-show="activeTestimonialType === 'sites'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($temoignages['sites'] as $commentaire)
                        <div class="bg-white rounded-xl p-6 shadow-lg mb-4 transform transition duration-300 hover:shadow-xl hover:-translate-y-1">
                            <!-- Star Rating -->
                            <div class="flex items-center mb-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $commentaire->note)
                                        <svg class="w-5 h-5 text-yellow-500 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">{{ $commentaire->note }}/5</span>
                            </div>
                            
                            <p class="text-gray-600 italic mb-4">{{ $commentaire->contenu }}</p>
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center overflow-hidden mr-4">
                                    <img src="{{ asset('images/1.png') }}" alt="Avatar de {{ $commentaire->user->nom }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $commentaire->user->nom }}</p>
                                    <p class="text-sm text-gray-500">{{ $commentaire->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Hébergements Testimonials -->
                <div 
                    x-show="activeTestimonialType === 'hebergements'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($temoignages['hebergements'] as $commentaire)
                        <div class="bg-white rounded-xl p-6 shadow-lg mb-4 transform transition duration-300 hover:shadow-xl hover:-translate-y-1">
                            <!-- Star Rating -->
                            <div class="flex items-center mb-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $commentaire->note)
                                        <svg class="w-5 h-5 text-yellow-500 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">{{ $commentaire->note }}/5</span>
                            </div>
                            
                            <p class="text-gray-600 italic mb-4">{{ $commentaire->contenu }}</p>
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center overflow-hidden mr-4">
                                    <img src="{{ asset('images/1.png') }}" alt="Avatar de {{ $commentaire->user->nom }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $commentaire->user->nom }}</p>
                                    <p class="text-sm text-gray-500">{{ $commentaire->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Circuits Testimonials -->
                <div 
                    x-show="activeTestimonialType === 'circuits'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($temoignages['circuits'] as $commentaire)
                        <div class="bg-white rounded-xl p-6 shadow-lg mb-4 transform transition duration-300 hover:shadow-xl hover:-translate-y-1">
                            <!-- Star Rating -->
                            <div class="flex items-center mb-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $commentaire->note)
                                        <svg class="w-5 h-5 text-yellow-500 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">{{ $commentaire->note }}/5</span>
                            </div>
                            
                            <p class="text-gray-600 italic mb-4">{{ $commentaire->contenu }}</p>
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center overflow-hidden mr-4">
                                    <img src="{{ asset('images/1.png') }}" alt="Avatar de {{ $commentaire->user->nom }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $commentaire->user->nom }}</p>
                                    <p class="text-sm text-gray-500">{{ $commentaire->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section 
        class="py-20 bg-cover bg-center relative" 
        style="background-image:  url('{{ asset('images/benin-culture.jpg') }}')">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-4xl font-bold text-white mb-6">Prêt à découvrir le Bénin ?</h2>
                <p class="text-xl text-gray-200 mb-10">Planifiez votre voyage dès maintenant et explorez la richesse du Bénin.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('contact.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-4 rounded-full font-semibold transition duration-300 ease-out hover:scale-105">
                        Contactez-nous
                    </a>
                    <a href="{{ route('circuits.index') }}" class="bg-transparent border-2 border-white text-white hover:bg-white/10 px-8 py-4 rounded-full font-semibold transition duration-300">
                        Nos circuits touristiques
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Initialisation d'Alpine.js si nécessaire
    });
</script>
@endpush