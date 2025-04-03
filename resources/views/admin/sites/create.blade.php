@extends('layouts.admin')

@section('title', 'Ajouter un Site Touristique')

@section('content')


<div class="bg-cover bg-center bg-fixed min-h-screen py-6" 
     style="background-image: url('/images/background.jpg');">
    <div " 
         x-data="siteEditor()">
        <!-- Reste du contenu... -->
    </div>



    <div class="flex flex-col md:flex-row items-start md:items-center justify-between py-5 mb-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Ajouter un Site Touristique
        </h1>
        <a href="{{ route('admin.sites.index') }}" class="mt-3 md:mt-0 inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition-colors duration-300 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>

    <div x-data="{ activeTab: 'info' }" class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Tabs -->
        <div class="flex border-b border-gray-200">
            <button 
                @click="activeTab = 'info'" 
                :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'info', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'info' }"
                class="py-4 px-6 border-b-2 font-medium text-sm transition duration-150 ease-in-out flex items-center"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informations Générales
            </button>
            <button 
                @click="activeTab = 'location'" 
                :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'location', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'location' }"
                class="py-4 px-6 border-b-2 font-medium text-sm transition duration-150 ease-in-out flex items-center"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
                Localisation
            </button>
            <button 
                @click="activeTab = 'media'" 
                :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'media', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'media' }"
                class="py-4 px-6 border-b-2 font-medium text-sm transition duration-150 ease-in-out flex items-center"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Médias
            </button>
        </div>

        <!-- Content -->
        <form action="{{ route('admin.sites.store') }}" method="POST" enctype="multipart/form-data" 
              x-data="{ 
                  images: [], 
                  previewImages() {
                      const input = document.getElementById('images');
                      this.images = [];
                      if (input.files) {
                          for (let i = 0; i < input.files.length; i++) {
                              const reader = new FileReader();
                              reader.onload = (e) => {
                                  this.images.push(e.target.result);
                                  this.$forceUpdate();
                              };
                              reader.readAsDataURL(input.files[i]);
                          }
                      }
                  },
                  removeImage(index) {
                      this.images.splice(index, 1);
                  }
              }">
            @csrf

            <div class="p-6" x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700">
                                Nom du site <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                                    class="pl-10 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-md border-gray-300 shadow-sm transition duration-150 ease-in-out @error('nom') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="Entrez le nom du site touristique">
                            </div>
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="categorie_id" class="block text-sm font-medium text-gray-700">
                                Catégorie <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <select id="categorie_id" name="categorie_id" required
                                    class="pl-10 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-md border-gray-300 shadow-sm transition duration-150 ease-in-out @error('categorie_id') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $categorie)
                                        <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                            {{ $categorie->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('categorie_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="5" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md transition duration-150 ease-in-out @error('description') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="Entrez une description détaillée du site touristique">{{ old('description') }}</textarea>
                        </div>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="p-6" x-show="activeTab === 'location'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-6">
                            <label for="localisation" class="block text-sm font-medium text-gray-700">
                                Localisation <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <input type="text" id="localisation" name="localisation" value="{{ old('localisation') }}" required
                                    class="pl-10 focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-md border-gray-300 shadow-sm transition duration-150 ease-in-out @error('localisation') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="Ville, Région, Pays">
                            </div>
                            @error('localisation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700">
                                    Latitude <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude') }}" required
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-md border-gray-300 shadow-sm transition duration-150 ease-in-out @error('latitude') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                </div>
                                @error('latitude')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700">
                                    Longitude <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude') }}" required
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-md border-gray-300 shadow-sm transition duration-150 ease-in-out @error('longitude') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                </div>
                                @error('longitude')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-2 shadow-inner">
                        <div id="map" class="w-full h-72 rounded-lg shadow-sm border border-gray-300 bg-white"></div>
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Cliquez sur la carte pour définir l'emplacement ou déplacez le marqueur.
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6" x-show="activeTab === 'media'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Images du site
                        </label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-400 transition-colors duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Télécharger des images</span>
                                        <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" @change="previewImages()">
                                    </label>
                                    <p class="pl-1">ou glissez-déposez</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2MB</p>
                            </div>
                        </div>
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="images.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <template x-for="(image, index) in images" :key="index">
                            <div class="relative group">
                                <img :src="image" class="h-32 w-full object-cover rounded-lg shadow-sm border border-gray-200">
                                <button type="button" @click="removeImage(index)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <a href="{{ route('admin.sites.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition transform hover:scale-[1.02] duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animations supplémentaires */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .form-control-focus-ring {
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }
    
    .map-focus-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(79, 70, 229, 0); }
        100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
    }
</style>
@endpush

@push('scripts')
<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>

<script>
// Variables globales pour Google Maps
let map;
let marker;

// Initialisation de la carte Google Maps
function initMap() {
    // Définir la vue initiale 
    const defaultLat = 6.428838;
    const defaultLng = 2.341125;
    
    // Si les champs ont déjà des valeurs, les utiliser
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const initialLat = latitudeInput && latitudeInput.value ? parseFloat(latitudeInput.value) : defaultLat;
    const initialLng = longitudeInput && longitudeInput.value ? parseFloat(longitudeInput.value) : defaultLng;
    
    const defaultPosition = {
        lat: initialLat,
        lng: initialLng
    };
    
    // Initialiser la carte avec un style personnalisé
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 6,
        center: defaultPosition,
        mapTypeControl: true,
        streetViewControl: false,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
        },
        styles: [
            {
                "featureType": "administrative",
                "elementType": "geometry",
                "stylers": [{"visibility": "off"}]
            },
            {
                "featureType": "poi",
                "stylers": [{"visibility": "off"}]
            },
            {
                "featureType": "road",
                "elementType": "labels.icon",
                "stylers": [{"visibility": "off"}]
            },
            {
                "featureType": "transit",
                "stylers": [{"visibility": "off"}]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{"color": "#c9e9ff"}]
            }
        ]
    });
    
    // Ajouter un marqueur animé à la position initiale
    marker = new google.maps.Marker({
        position: defaultPosition,
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 10,
            fillColor: "#4F46E5",
            fillOpacity: 0.8,
            strokeWeight: 2,
            strokeColor: "#FFFFFF"
        }
    });
    
    // Fonction pour mettre à jour les champs de coordonnées avec animation
    function updateCoordinates(latLng) {
        if (latitudeInput && longitudeInput) {
            // Animation pour la mise à jour
            latitudeInput.classList.add('bg-indigo-50');
            longitudeInput.classList.add('bg-indigo-50');
            
            latitudeInput.value = latLng.lat().toFixed(6);
            longitudeInput.value = latLng.lng().toFixed(6);
            
            // Retirer l'animation après un délai
            setTimeout(() => {
                latitudeInput.classList.remove('bg-indigo-50');
                longitudeInput.classList.remove('bg-indigo-50');
            }, 500);
        }
    }
    
    // Mettre à jour les coordonnées quand le marqueur est déplacé
    marker.addListener('dragend', function() {
        updateCoordinates(marker.getPosition());
        
        // Animation bounce après déplacement
        if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(() => {
                marker.setAnimation(null);
            }, 750);
        }
    });
    
    // Mettre à jour le marqueur et les champs quand on clique sur la carte
    map.addListener('click', function(e) {
        marker.setPosition(e.latLng);
        updateCoordinates(e.latLng);
        
        // Animation de zoom
        map.panTo(e.latLng);
    });
    
    // Mettre à jour le marqueur si les coordonnées sont saisies manuellement
    if (latitudeInput && longitudeInput) {
        latitudeInput.addEventListener('change', updateMarkerFromInputs);
        longitudeInput.addEventListener('change', updateMarkerFromInputs);
    }
    
    function updateMarkerFromInputs() {
        const lat = parseFloat(latitudeInput.value) || defaultLat;
        const lng = parseFloat(longitudeInput.value) || defaultLng;
        
        const newPosition = {
            lat: lat,
            lng: lng
        };
        
        marker.setPosition(newPosition);
        
        // Animation de transition
        map.panTo(newPosition);
    }
    
    // Recherche de localisation avec Google Geocoding API
    const localisationInput = document.getElementById('localisation');
    if (localisationInput) {
        localisationInput.addEventListener('blur', function() {
            const query = this.value;
            
            if (query && typeof google !== 'undefined') {
                // Ajouter une classe pour indiquer la recherche en cours
                localisationInput.classList.add('bg-indigo-50');
                
                const geocoder = new google.maps.Geocoder();
                                    // Continuation du script précédent pour la fonction geocode
geocoder.geocode({ 'address': query }, function(results, status) {
    // Retirer la classe après la recherche
    localisationInput.classList.remove('bg-indigo-50');
    
    if (status === 'OK' && results[0]) {
        const location = results[0].geometry.location;
        
        // Mettre à jour la carte et le marqueur
        map.setCenter(location);
        map.setZoom(10);
        marker.setPosition(location);
        
        // Mettre à jour les champs de coordonnées
        updateCoordinates(location);
        
        // Animation pour montrer que les coordonnées ont été trouvées
        marker.setAnimation(google.maps.Animation.BOUNCE);
        setTimeout(() => {
            marker.setAnimation(null);
        }, 750);
    }
});
            }
        });
    }
}

// Initialiser Alpine.js lorsque tout est chargé
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les aperçus d'images si des images ont déjà été téléchargées
    const imagesInput = document.getElementById('images');
    if (imagesInput && imagesInput.files.length > 0) {
        window.dispatchEvent(new Event('previewImages'));
    }
    
    // Validations côté client
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            let valid = true;
            
            // Validation du nom
            const nomInput = document.getElementById('nom');
            if (nomInput && nomInput.value.trim() === '') {
                nomInput.classList.add('border-red-500');
                valid = false;
            } else if (nomInput) {
                nomInput.classList.remove('border-red-500');
            }
            
            // Validation de la catégorie
            const categorieInput = document.getElementById('categorie_id');
            if (categorieInput && categorieInput.value === '') {
                categorieInput.classList.add('border-red-500');
                valid = false;
            } else if (categorieInput) {
                categorieInput.classList.remove('border-red-500');
            }
            
            // Validation de la description
            const descriptionInput = document.getElementById('description');
            if (descriptionInput && descriptionInput.value.trim() === '') {
                descriptionInput.classList.add('border-red-500');
                valid = false;
            } else if (descriptionInput) {
                descriptionInput.classList.remove('border-red-500');
            }
            
            // Validation des coordonnées
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            if ((latitudeInput && latitudeInput.value === '') || 
                (longitudeInput && longitudeInput.value === '')) {
                if (latitudeInput) latitudeInput.classList.add('border-red-500');
                if (longitudeInput) longitudeInput.classList.add('border-red-500');
                valid = false;
            } else {
                if (latitudeInput) latitudeInput.classList.remove('border-red-500');
                if (longitudeInput) longitudeInput.classList.remove('border-red-500');
            }
            
            // Empêcher la soumission si le formulaire n'est pas valide
            if (!valid) {
                e.preventDefault();
                // Afficher un message d'erreur
                const errorMessage = document.createElement('div');
                errorMessage.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg';
                errorMessage.textContent = 'Veuillez corriger les erreurs dans le formulaire.';
                document.body.appendChild(errorMessage);
                
                // Supprimer le message après un délai
                setTimeout(() => {
                    errorMessage.style.opacity = '0';
                    errorMessage.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(() => {
                        document.body.removeChild(errorMessage);
                    }, 500);
                }, 3000);
            }
        });
    }
});
</script>
@endpush