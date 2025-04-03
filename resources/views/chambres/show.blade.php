@extends('layouts.app')

@section('title', $chambre->nom)

@section('content')
<div x-data="{
    activeSection: 'description',
    activeImageIndex: 0,
    showBookingForm: false,
    dates: {
        checkIn: '',
        checkOut: '',
        guests: 1
    },
    get totalNights() {
        if (!this.dates.checkIn || !this.dates.checkOut) return 0;
        const start = new Date(this.dates.checkIn);
        const end = new Date(this.dates.checkOut);
        return Math.floor((end - start) / (1000 * 60 * 60 * 24));
    },
    get totalPrice() {
        return this.totalNights * {{ $chambre->prix }};
    }
}" class=" min-h-screen">

    <!-- Hero Section -->
    <div class="relative h-80 sm:h-96 md:h-[500px] overflow-hidden">
        @if($chambre->medias->count() > 0)
            <img src="{{ asset('storage/' . $chambre->medias->first()->url) }}"
                 alt="{{ $chambre->nom }}"
                 class="w-full h-full object-cover object-center">
        @else
            <img src="{{ asset('images/default-room.jpg') }}"
                 alt="{{ $chambre->nom }}"
                 class="w-full h-full object-cover object-center">
        @endif
        
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        
        <div class="absolute inset-0 flex flex-col justify-center px-6 sm:px-12">
            <div class="max-w-4xl mx-auto text-white">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-2 text-white"
                    x-intersect:enter="transition duration-500 ease-out transform translate-y-8 opacity-0"
                    x-intersect:enter-end="translate-y-0 opacity-100">
                    {{ $chambre->nom }}
                </h1>
                
                <div class="flex items-center mb-4 text-yellow-400">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ $i <= ($chambre->hebergement->commentaires->avg('note') ?? 0) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    @endfor
                    <span class="ml-2 text-white">({{ $chambre->hebergement->commentaires->count() }} avis)</span>
                </div>
                
                <div class="flex flex-wrap gap-4 mb-4">
                    <div class="flex items-center bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>{{ number_format($chambre->prix, 2) }} € / nuit</span>
                    </div>
                    
                    <div class="flex items-center bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>{{ $chambre->capacite }} personne(s)</span>
                    </div>
                    
                    <div class="flex items-center bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6-10l2 2" />
                        </svg>
                        <span>{{ $chambre->hebergement->typeHebergement->nom }}</span>
                    </div>
                </div>
                
                <div class="mt-6">
                    <a href=" {{ route('touriste.reservations.hebergements.create', $chambre->id ) }} "
                            class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-md transition duration-300 transform hover:scale-105">
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
                    @click="activeSection = 'equipements'" 
                    :class="{'border-yellow-500 text-yellow-600': activeSection === 'equipements', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'equipements'}"
                    class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Équipements
                </button>
                <button 
                    @click="activeSection = 'emplacement'" 
                    :class="{'border-yellow-500 text-yellow-600': activeSection === 'emplacement', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'emplacement'}"
                    class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Emplacement
                </button>
                <button 
                    @click="activeSection = 'avis'" 
                    :class="{'border-yellow-500 text-yellow-600': activeSection === 'avis', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'avis'}"
                    class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Avis ({{ $chambre->hebergement->commentaires->count() }})
                </button>
                <button 
                    @click="activeSection = 'galerie'" 
                    :class="{'border-yellow-500 text-yellow-600': activeSection === 'galerie', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'galerie'}"
                    class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Galerie
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
            <h2 class="text-2xl font-bold mb-4 text-gray-800">À propos de cette chambre</h2>
            <div class="prose max-w-none text-gray-600">
                {!! nl2br(e($chambre->description)) !!}
            </div>
            
            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">À propos de l'hébergement</h3>
                <div class="prose max-w-none text-gray-600">
                    <h4 class="text-lg font-medium mb-2">{{ $chambre->hebergement->nom }}</h4>
                    {!! nl2br(e($chambre->hebergement->description)) !!}
                </div>
            </div>
        </div>

        <!-- Équipements Section -->
        <div x-show="activeSection === 'equipements'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Équipements et services</h2>
            
            @if($chambre->equipements->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach(['Chambre', 'Salle de bain', 'Divertissement', 'Autres'] as $type)
                        @php
                            $items = $type === 'Autres' 
                                ? $chambre->equipements->whereNotIn('type', ['chambre', 'salle_de_bain', 'divertissement'])->pluck('nom')
                                : $chambre->equipements->where('type', strtolower(str_replace(' ', '_', $type)))->pluck('nom');
                        @endphp
                        @if($items->count() > 0)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold mb-2 text-gray-800">{{ $type }}</h3>
                                <ul class="space-y-2 text-gray-600">
                                    @foreach($items as $item)
                                        <li class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ $item }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    Aucun équipement spécifié pour cette chambre.
                </div>
            @endif
        </div>

        <!-- Emplacement Section avec Google Maps -->
        <div x-show="activeSection === 'emplacement'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Emplacement</h2>
            
            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-1/3">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">Adresse</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6-10l2 2" />
                                </svg>
                                {{ $chambre->hebergement->adresse }}
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $chambre->hebergement->ville }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="md:w-2/3">
                    <div class="bg-gray-50 rounded-lg h-96 overflow-hidden">
                        <div id="map" class="w-full h-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avis Section -->
        <div x-show="activeSection === 'avis'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Avis des clients</h2>
                <div class="flex items-center">
                    <div class="flex items-center text-yellow-400">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ $i <= ($chambre->hebergement->commentaires->avg('note') ?? 0) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        @endfor
                    </div>
                    <span class="ml-2 text-gray-600">{{ number_format($chambre->hebergement->commentaires->avg('note') ?? 0, 1) }} sur 5 ({{ $chambre->hebergement->commentaires->count() }} avis)</span>
                </div>
            </div>
            
            @if($chambre->hebergement->commentaires->count() > 0)
                <div class="space-y-6">
                    @foreach($chambre->hebergement->commentaires as $commentaire)
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                            <div class="flex justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($commentaire->user->nom ?? 'Anonyme', 0, 1) }}{{ substr($commentaire->user->prenom ?? 'Anonyme', 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-semibold text-gray-800">{{ $commentaire->user->nom ?? 'Anonyme' }} {{ $commentaire->user->prenom ?? 'Anonyme' }}</h4>
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
                    <p class="text-xl text-gray-500">Aucun avis pour cette chambre</p>
                    <p class="text-gray-500 mt-2">Soyez le premier à partager votre expérience !</p>
                </div>
            @endif
        </div>

        <!-- Galerie Section -->
        <div x-show="activeSection === 'galerie'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Galerie d'images</h2>
            
            @if($chambre->medias->count() > 0)
                <div class="relative">
                    <!-- Main Image Preview -->
                    <div class="relative h-96 mb-4 rounded-lg overflow-hidden shadow-lg">
                        <template x-for="(image, index) in {{ json_encode($chambre->medias->map(fn($media) => asset('storage/' . $media->url))->toArray()) }}" :key="index">
                            <img 
                                :src="image" 
                                x-show="activeImageIndex === index"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                class="w-full h-full object-cover" 
                                alt="{{ $chambre->nom }}">
                        </template>
                        
                        <!-- Navigation Arrows -->
                        <button 
                            @click="activeImageIndex = (activeImageIndex - 1 + {{ $chambre->medias->count() }}) % {{ $chambre->medias->count() }}"
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 text-white transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button 
                            @click="activeImageIndex = (activeImageIndex + 1) % {{ $chambre->medias->count() }}"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 text-white transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Thumbnails -->
                    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                        @foreach($chambre->medias as $index => $media)
                            <div 
                                @click="activeImageIndex = {{ $index }}"
                                :class="{'ring-2 ring-yellow-500': activeImageIndex === {{ $index }}}"
                                class="h-16 rounded-md overflow-hidden cursor-pointer hover:opacity-90 transition duration-300">
                                <img src="{{ asset('storage/' . $media->url) }}" class="w-full h-full object-cover" alt="Thumbnail">
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    Aucune image disponible pour cette chambre.
                </div>
            @endif
        </div>
    </div>

    <!-- Scripts pour Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=VOTRE_CLE_API&callback=initMap" async defer></script>
    <script>
        function initMap() {
            const hebergement = {
                lat: {{ $chambre->hebergement->latitude }},
                lng: {{ $chambre->hebergement->longitude }}
            };
            const map = new google.maps.Map(document.getElementById('map'), {
                center: hebergement,
                zoom: 15
            });
            const marker = new google.maps.Marker({
                position: hebergement,
                map: map,
                title: '{{ $chambre->hebergement->nom }}'
            });
        }
    </script>
</div>
@endsection