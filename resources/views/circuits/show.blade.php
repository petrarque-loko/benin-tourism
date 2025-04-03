@extends('layouts.app')

@section('title', $circuit->nom)

@section('content')
<div x-data="{
    activeMediaTab: 'images',
    activeImageIndex: 0,
    showCommentForm: false,
    rating: 0,
    activeSection: 'description'
}" class="bg-gray-50">
    <!-- Hero Section -->
    <div class="relative h-80 sm:h-96 md:h-[500px] overflow-hidden">
        <img src="{{ count($images) > 0 ? asset('storage/' . $images[0]->url) : asset('images/bg2.jpeg') }}" alt="Image" 
            class="w-full h-full object-cover object-center" 
            alt="{{ $circuit->nom }}">
        
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        
        <div class="absolute inset-0 flex flex-col justify-center px-6 sm:px-12">
            <div class="max-w-4xl mx-auto text-white">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-2 text-white" 
                    x-intersect:enter="transition duration-500 ease-out transform translate-y-8 opacity-0" 
                    x-intersect:enter-end="translate-y-0 opacity-100">
                    {{ $circuit->nom }}
                </h1>
                
                <div class="flex items-center mb-4 text-yellow-400">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ $i <= $noteMoyenne ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    @endfor
                    <span class="ml-2 text-white">({{ $commentaires->count() }} avis)</span>
                </div>
                
                <div class="flex flex-wrap gap-4 mb-4">
                    <div class="flex items-center bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $circuit->duree }} jours</span>
                    </div>
                    
                    <div class="flex items-center bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                        <span>{{ $circuit->difficulte }}</span>
                    </div>
                    
                    <div class="flex items-center bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>{{ number_format($circuit->prix, 2) }} €</span>
                    </div>
                </div>
                
                <div class="mt-6">
                    <a href=" {{ route('touriste.reservations.circuits.create', $circuit->id) }} " class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-md transition duration-300 transform hover:scale-105">
                        Réserver maintenant
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="sticky top-0 z-10 bg-white shadow-md">
        <div class="max-w-6xl mx-auto px-4">
            <nav class="flex space-x-1 overflow-x-auto scrollbar-hide" aria-label="Tabs">
                <button 
                    @click="activeSection = 'description'" 
                    :class="{'border-yellow-500 text-yellow-600': activeSection === 'description', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'description'}"
                    class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Description
                </button>
                <button 
                    @click="activeSection = 'itineraire'" 
                    :class="{'border-yellow-500 text-yellow-600': activeSection === 'itineraire', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'itineraire'}"
                    class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Itinéraire
                </button>
                <button 
                    @click="activeSection = 'galerie'" 
                    :class="{'border-yellow-500 text-yellow-600': activeSection === 'galerie', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'galerie'}"
                    class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Galerie
                </button>
                <button 
                    @click="activeSection = 'avis'" 
                    :class="{'border-yellow-500 text-yellow-600': activeSection === 'avis', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'avis'}"
                    class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Avis ({{ $commentaires->count() }})
                </button>
                <button 
                    @click="activeSection = 'guide'" 
                    :class="{'border-yellow-500 text-yellow-600': activeSection === 'guide', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'guide'}"
                    class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Guide
                </button>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Description Section -->
        <div x-show="activeSection === 'description'" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">À propos de ce circuit</h2>
            <div class="prose max-w-none text-gray-600">
                {!! $circuit->description !!}
            </div>
            
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="flex items-center text-lg font-semibold mb-2 text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Ce qui est inclus
                    </h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Guide professionnel
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Entrées aux sites
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Assistance 24/7
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="flex items-center text-lg font-semibold mb-2 text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informations
                    </h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Durée: {{ $circuit->duree }} jours
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Prix: {{ number_format($circuit->prix, 2) }} €
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ count($sitesOrdre) }} lieux à visiter
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="flex items-center text-lg font-semibold mb-2 text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Points importants
                    </h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Difficulté: {{ $circuit->difficulte }}
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Équipement recommandé
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Réservation 48h à l'avance
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Itinéraire Section -->
        <div x-show="activeSection === 'itineraire'" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Itinéraire du circuit</h2>
            
            <div class="relative">
                <!-- Vertical Line -->
                <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-yellow-500"></div>
                
                <!-- Timeline Items -->
                <div class="space-y-12">
                    @foreach($sitesOrdre as $index => $site)
                    <div class="relative" 
                        x-intersect:enter="transition duration-700 ease-out transform translate-y-16 opacity-0" 
                        x-intersect:enter-end="translate-y-0 opacity-100"
                        x-intersect:leave="transition duration-300 ease-in">
                        <div class="flex items-start">
                            <!-- Dot in timeline -->
                            <div class="flex-shrink-0 inline-flex bg-yellow-500 text-white rounded-full h-16 w-16 justify-center items-center border-4 border-white shadow-md z-10">
                                <span class="text-lg font-bold">{{ $index + 1 }}</span>
                            </div>
                            
                            <!-- Content -->
                            <div class="ml-6 w-full">
                                <div class="bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden">
                                    @if($site->medias->where('type', 'image')->first())
                                    <div class="relative h-48 overflow-hidden">
                                        <img src="{{ asset('storage/'.$site->medias->where('type', 'image')->first()->url) }}" 
                                            class="w-full h-full object-cover transform hover:scale-105 transition duration-500" 
                                            alt="{{ $site->nom }}">
                                    </div>
                                    @endif
                                    
                                    <div class="p-5">
                                        <div class="flex items-center mb-2">
                                            @if($site->categorie)
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-2">
                                                {{ $site->categorie->nom }}
                                            </span>
                                            @endif
                                            <h3 class="text-xl font-bold text-gray-800">{{ $site->nom }}</h3>
                                        </div>
                                        
                                        <p class="text-gray-600 mb-4">{{ Str::limit($site->description, 150) }}</p>
                                        
                                        <a href=" {{ route('sites.show', $site->id ) }} "
                                            class="inline-flex items-center text-yellow-600 hover:text-yellow-800">
                                            En savoir plus
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Galerie Section -->
        <div x-show="activeSection === 'galerie'" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Galerie média</h2>
            
            <!-- Media Type Selector -->
            <div class="flex space-x-4 mb-6 border-b">
                <button 
                    @click="activeMediaTab = 'images'" 
                    :class="{'border-yellow-500 text-yellow-600': activeMediaTab === 'images', 'border-transparent text-gray-500 hover:text-gray-700': activeMediaTab !== 'images'}"
                    class="px-4 py-2 font-medium border-b-2 focus:outline-none">
                    Images ({{ $images->count() }})
                </button>
                <button 
                    @click="activeMediaTab = 'videos'" 
                    :class="{'border-yellow-500 text-yellow-600': activeMediaTab === 'videos', 'border-transparent text-gray-500 hover:text-gray-700': activeMediaTab !== 'videos'}"
                    class="px-4 py-2 font-medium border-b-2 focus:outline-none">
                    Vidéos ({{ $videos->count() }})
                </button>
            </div>
            
            <!-- Images Tab -->
            <div x-show="activeMediaTab === 'images'" class="mb-6">
                <div x-show="$images->count() > 0" class="relative">
                    <!-- Main Image Preview -->
                    <div class="relative h-96 mb-4 rounded-lg overflow-hidden shadow-lg">
                    <template x-for="(image, index) in {{ json_encode($images->map(fn($image) => asset('storage/' . $image->url))->toArray()) }}" :key="index">
                            <img 
                                :src="image" 
                                x-show="activeImageIndex === index"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                class="w-full h-full object-cover" 
                                alt="Site touristique image">
                        </template>
                        
                        <!-- Navigation Arrows -->
                        <button 
                            @click="activeImageIndex = (activeImageIndex - 1 + {{ $images->count() }}) % {{ $images->count() }}"
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 text-white transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button 
                            @click="activeImageIndex = (activeImageIndex + 1) % {{ $images->count() }}"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 text-white transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Thumbnails -->
                    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                        @foreach($images as $index => $image)
                        <div 
                            @click="activeImageIndex = {{ $index }}"
                            :class="{'ring-2 ring-yellow-500': activeImageIndex === {{ $index }}}"
                            class="h-16 rounded-md overflow-hidden cursor-pointer hover:opacity-90 transition duration-300">
                            <img src="{{ asset('storage/' . $image->url) }}" class="w-full h-full object-cover" alt="Thumbnail">
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div x-show="{{ $images->count() }} === 0" class="text-center py-12 text-gray-500">
                    Aucune image disponible pour ce circuit.
                </div>
            </div>
            
            <!-- Videos Tab -->
            <div x-show="activeMediaTab === 'videos'">
                @if($videos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($videos as $video)
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <video controls class="w-full h-auto">
                            <source src="{{ $video->url }}" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                        <div class="p-4 bg-gray-50">
                            <h4 class="font-medium text-gray-800">{{ $video->titre ?? 'Vidéo du circuit' }}</h4>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 text-gray-500">
                    Aucune vidéo disponible pour ce circuit.
                </div>
                @endif
            </div>
        </div>

        <!-- Avis Section -->
        <div x-show="activeSection === 'avis'" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Avis des voyageurs</h2>
                <div class="flex items-center">
                    <div class="flex items-center text-yellow-400">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ $i <= $noteMoyenne ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        @endfor
                    </div>
                    <span class="ml-2 text-gray-600">{{ number_format($noteMoyenne, 1) }} sur 5 ({{ $commentaires->count() }} avis)</span>
                </div>
            </div>
            
            <!-- Ajouter un commentaire -->
            <div class="mb-8">
                <button 
                    @click="showCommentForm = !showCommentForm" 
                    class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Écrire un avis
                </button>
                
                <div x-show="showCommentForm" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="mt-4 p-6 bg-gray-50 rounded-lg shadow-inner">
                    <h3 class="text-xl font-semibold mb-4">Partagez votre expérience</h3>
                    
                    <form action="{{ route('circuits.comment', $circuit->id) }}" method="post">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Votre note</label>
                            <div class="flex items-center">
                                <template x-for="star in 5">
                                    <button 
                                        type="button"
                                        @click="rating = star" 
                                        class="focus:outline-none">
                                        <svg 
                                            :class="{'text-yellow-400': star <= rating, 'text-gray-300': star > rating}"
                                            class="w-8 h-8 mr-1 hover:text-yellow-400 transition duration-200"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" name="note" x-model="rating">
                        </div>
                        
                        <div class="mb-4">
                            <label for="contenu" class="block text-gray-700 mb-2">Votre commentaire</label>
                            <textarea 
                                id="contenu" 
                                name="contenu" 
                                rows="4" 
                                class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring focus:ring-yellow-200"
                                placeholder="Partagez votre expérience avec ce circuit..."></textarea>
                        </div>
                        
                        <div class="flex justify-end">
                            <button 
                                type="button" 
                                @click="showCommentForm = false"
                                class="px-4 py-2 mr-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-300">
                                Annuler
                            </button>
                            <button 
                                type="submit" 
                                class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-300">
                                Publier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Liste des commentaires -->
            @if($commentaires->count() > 0)
            <div class="space-y-6">
                @foreach($commentaires as $commentaire)
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <div class="flex justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($commentaire->user->nom, 0, 1) }}{{ substr($commentaire->user->prenom, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-800">{{ $commentaire->user->nom }} {{ $commentaire->user->prenom }}</h4>
                                <span class="text-sm text-gray-500">{{ $commentaire->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="{{ $i <= $commentaire->note ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-600">{{ $commentaire->contenu }}</p>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-10 bg-gray-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
                <p class="text-xl text-gray-500">Aucun avis pour ce circuit</p>
                <p class="text-gray-500 mt-2">Soyez le premier à partager votre expérience !</p>
            </div>
            @endif
        </div>

        <!-- Guide Section -->
        <div x-show="activeSection === 'guide'" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Votre guide</h2>
            
            @if($circuit->guide)
            <div class="flex flex-col md:flex-row items-center md:items-start">
                <div class="w-32 h-32 rounded-full overflow-hidden mb-4 md:mb-0 md:mr-6 shadow-md">
                    <img src="{{ $circuit->guide->photo ?? '/images/3.jpeg' }}" 
                        class="w-full h-full object-cover" 
                        alt="{{ $circuit->guide->nom }}">
                </div>
                
                <div class="text-center md:text-left">
                    <a href=" {{ route('users.show', $circuit->guide->id ) }} ">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $circuit->guide->nom }} {{ $circuit->guide->prenom }}</h3>
                    </a>
                    <div class="flex flex-wrap md:flex-nowrap gap-2 mb-4 justify-center md:justify-start">
                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                            {{ $circuit->guide->langues ?? 'Français, Anglais' }}
                        </span>
                        
                        <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $circuit->guide->experiences ?? '5+ ans d\'expérience' }}
                        </span>
                    </div>
                    
                    <p class="text-gray-600 mb-4">{{ $circuit->guide->bio ?? 'Guide professionnel passionné et connaisseur de la région.' }}</p>
                    
                    <div class="flex space-x-3 justify-center md:justify-start">
                        @if($circuit->guide->reseaux_sociaux ?? false)
                            @foreach(json_decode($circuit->guide->reseaux_sociaux) as $reseau => $url)
                                <a href="{{ $url }}" target="_blank" class="text-gray-500 hover:text-gray-800">
                                    <i class="fab fa-{{ $reseau }} text-xl"></i>
                                </a>
                            @endforeach
                        @else
                            <a href="#" class="text-gray-500 hover:text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-pink-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-10 bg-gray-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <p class="text-xl text-gray-500">Information sur le guide non disponible</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Site touristique detail modal -->
<div 
    x-data="{ open: false, siteName: '', siteDescription: '', siteId: null }"
    x-show="open"
    @open-modal.window="open = true; siteName = $event.detail.siteName; siteDescription = $event.detail.siteDescription; siteId = $event.detail.siteId"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto" 
    style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="open = false"></div>
        
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 transform translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle bg-white rounded-2xl shadow-xl transform transition-all">
            
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button @click="open = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="mt-4">
                <h3 class="text-2xl font-bold text-gray-800 mb-2" x-text="siteName"></h3>
                <p class="text-gray-600" x-text="siteDescription"></p>
                
                <!-- Site touristique gallery -->
                <div class="mt-6">
                    <h4 class="text-lg font-semibold mb-4">Photos</h4>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($sitesOrdre as $site)
                        <template x-if="siteId === {{ $site->id }}">
                            @foreach($site->medias->where('type', 'image')->take(4) as $media)
                            <div class="rounded-lg overflow-hidden">
                                <img src="{{ $media->url }}" class="w-full h-32 object-cover" alt="{{ $site->nom }}">
                            </div>
                            @endforeach
                        </template>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="mt-8">
                <button @click="open = false" class="float-right px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-300">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection