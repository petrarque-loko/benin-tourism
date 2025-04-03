@extends('layouts.admin')

@section('title', 'Modifier un Site Touristique')

@section('content')
<div class="container-fluid" x-data="siteEditor()">
    <!-- En-tête avec animation -->
    <div class="flex items-center justify-between mb-6 overflow-hidden rounded-lg bg-gradient-to-r from-purple-600 to-indigo-800 shadow-lg transition-all duration-500 hover:shadow-xl">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-white mb-1">Modifier un Site Touristique</h1>
            <p class="text-purple-200 text-sm">Personnalisez les informations et ajoutez des médias pour attirer plus de visiteurs</p>
        </div>
        <div class="p-6 hidden sm:block">
            <div class="text-white rounded-full bg-purple-500 bg-opacity-30 p-3 transition-transform duration-500 hover:rotate-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Formulaire principal -->
    <div class="bg-white rounded-xl shadow-lg mb-8 overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="bg-gradient-to-r from-indigo-100 to-purple-50 p-4 border-l-4 border-indigo-500">
            <h2 class="text-lg font-semibold text-indigo-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Informations du site
            </h2>
        </div>
        
        <form action="{{ route('admin.sites.update', $site) }}" method="POST" enctype="multipart/form-data" class="p-6" id="siteForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Informations de base -->
                <div class="space-y-6">
                    <div class="relative" x-data="{ focused: false, filled: '{{ old('nom', $site->nom) }}' !== '' }">
                        <input
                            type="text"
                            id="nom"
                            name="nom"
                            class="peer w-full border-b-2 border-gray-300 px-3 py-3 placeholder-transparent focus:border-indigo-500 focus:outline-none transition-colors duration-300 @error('nom') border-red-500 @enderror"
                            placeholder="Nom du site"
                            value="{{ old('nom', $site->nom) }}"
                            required
                            @focus="focused = true"
                            @blur="focused = document.getElementById('nom').value !== ''"
                        >
                        <label 
                            for="nom" 
                            class="absolute left-3 -top-1 text-sm text-gray-500 transition-all 
                                  peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3
                                  peer-focus:-top-1 peer-focus:text-sm peer-focus:text-indigo-600"
                            :class="{ 'text-indigo-600 -top-1 text-sm': focused || filled }"
                        >
                            Nom du site <span class="text-red-500">*</span>
                        </label>
                        @error('nom')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="categorie_id" class="block text-sm font-medium text-indigo-600 mb-1">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="categorie_id"
                                name="categorie_id"
                                class="block w-full pl-3 pr-10 py-3 text-base border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 appearance-none @error('categorie_id') border-red-500 @enderror"
                                required
                            >
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}" {{ old('categorie_id', $site->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                        {{ $categorie->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-indigo-500">
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        @error('categorie_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative" x-data="{ focused: false, filled: '{{ old('localisation', $site->localisation) }}' !== '' }">
                        <input
                            type="text"
                            id="localisation"
                            name="localisation"
                            class="peer w-full border-b-2 border-gray-300 px-3 py-3 placeholder-transparent focus:border-indigo-500 focus:outline-none transition-colors duration-300 @error('localisation') border-red-500 @enderror"
                            placeholder="Localisation"
                            value="{{ old('localisation', $site->localisation) }}"
                            required
                            @focus="focused = true"
                            @blur="focused = document.getElementById('localisation').value !== ''; searchLocation()"
                        >
                        <label 
                            for="localisation" 
                            class="absolute left-3 -top-1 text-sm text-gray-500 transition-all 
                                  peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3
                                  peer-focus:-top-1 peer-focus:text-sm peer-focus:text-indigo-600"
                            :class="{ 'text-indigo-600 -top-1 text-sm': focused || filled }"
                        >
                            Localisation <span class="text-red-500">*</span>
                        </label>
                        <div class="absolute right-2 top-3 text-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        @error('localisation')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative" x-data="{ focused: false, filled: '{{ old('latitude', $site->latitude) }}' !== '' }">
                            <input
                                type="number"
                                step="any"
                                id="latitude"
                                name="latitude"
                                class="peer w-full border-b-2 border-gray-300 px-3 py-3 placeholder-transparent focus:border-indigo-500 focus:outline-none transition-colors duration-300 @error('latitude') border-red-500 @enderror"
                                placeholder="Latitude"
                                value="{{ old('latitude', $site->latitude) }}"
                                required
                                @focus="focused = true"
                                @blur="focused = document.getElementById('latitude').value !== ''"
                                @input="updateMarkerFromInputs()"
                            >
                            <label 
                                for="latitude" 
                                class="absolute left-3 -top-1 text-sm text-gray-500 transition-all 
                                      peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3
                                      peer-focus:-top-1 peer-focus:text-sm peer-focus:text-indigo-600"
                                :class="{ 'text-indigo-600 -top-1 text-sm': focused || filled }"
                            >
                                Latitude <span class="text-red-500">*</span>
                            </label>
                            @error('latitude')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="relative" x-data="{ focused: false, filled: '{{ old('longitude', $site->longitude) }}' !== '' }">
                            <input
                                type="number"
                                step="any"
                                id="longitude"
                                name="longitude"
                                class="peer w-full border-b-2 border-gray-300 px-3 py-3 placeholder-transparent focus:border-indigo-500 focus:outline-none transition-colors duration-300 @error('longitude') border-red-500 @enderror"
                                placeholder="Longitude"
                                value="{{ old('longitude', $site->longitude) }}"
                                required
                                @focus="focused = true"
                                @blur="focused = document.getElementById('longitude').value !== ''"
                                @input="updateMarkerFromInputs()"
                            >
                            <label 
                                for="longitude" 
                                class="absolute left-3 -top-1 text-sm text-gray-500 transition-all 
                                      peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3
                                      peer-focus:-top-1 peer-focus:text-sm peer-focus:text-indigo-600"
                                :class="{ 'text-indigo-600 -top-1 text-sm': focused || filled }"
                            >
                                Longitude <span class="text-red-500">*</span>
                            </label>
                            @error('longitude')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="relative" 
                         x-data="{ 
                             isUploading: false, 
                             progress: 0,
                             previewOpen: false,
                             fileList: [],
                             previewImage: ''
                         }"
                         x-init="$watch('isUploading', value => {
                             if (value) {
                                 let interval = setInterval(() => {
                                     progress = Math.min(progress + 10, 90);
                                 }, 300);
                                 setTimeout(() => {
                                     clearInterval(interval);
                                     progress = 100;
                                     setTimeout(() => {
                                         isUploading = false;
                                         progress = 0;
                                     }, 400);
                                 }, 2000);
                             }
                         })">
                        <label class="block text-sm font-medium text-indigo-600 mb-1">
                            Ajouter des images
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-lg transition-all hover:border-indigo-400 group">
                            <div class="space-y-1 text-center">
                                <svg
                                    class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition-colors duration-300"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 48 48"
                                >
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label
                                        for="images"
                                        class="relative cursor-pointer bg-indigo-50 rounded-md font-medium text-indigo-600 hover:text-indigo-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 transition-colors px-2 py-1"
                                    >
                                        <span>Sélectionner des fichiers</span>
                                        <input 
                                            id="images" 
                                            name="images[]" 
                                            type="file" 
                                            class="sr-only" 
                                            multiple 
                                            accept="image/*"
                                            @change="
                                                fileList = Array.from($event.target.files);
                                                document.getElementById('customFileLabel').textContent = fileList.map(f => f.name).join(', ') || 'Choisir des fichiers...';
                                                isUploading = true;
                                                handlePreview($event);
                                            "
                                        >
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    JPG, PNG, GIF (Max 2Mo par image)
                                </p>
                                <p id="customFileLabel" class="text-xs text-gray-500 truncate max-w-xs mx-auto">
                                    Choisir des fichiers...
                                </p>
                            </div>
                        </div>
                        
                        <!-- Barre de progression -->
                        <div class="mt-2 overflow-hidden rounded-full bg-gray-200" x-show="isUploading" x-transition.opacity>
                            <div 
                                class="h-2 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-300" 
                                :style="`width: ${progress}%`"
                            ></div>
                        </div>
                        
                        <!-- Prévisualisation des images -->
                        <div class="grid grid-cols-3 gap-2 mt-2" id="preview"></div>
                        
                        <!-- Modal de prévisualisation -->
                        <div 
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity"
                            x-show="previewOpen" 
                            x-transition
                            @click.self="previewOpen = false"
                        >
                            <div class="bg-white p-2 rounded-lg max-w-3xl max-h-3/4 overflow-auto">
                                <img :src="previewImage" alt="Prévisualisation" class="max-h-full">
                                <button 
                                    class="mt-2 p-2 bg-red-500 text-white rounded-full absolute top-4 right-4 hover:bg-red-600 transition-colors"
                                    @click="previewOpen = false"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        @error('images.*')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Carte Google Maps -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-indigo-600 mb-1">
                            Sélectionnez l'emplacement sur la carte
                        </label>
                        <div 
                            id="map" 
                            class="w-full h-80 rounded-lg shadow-md border-2 border-indigo-100 transition-all duration-300 hover:shadow-lg"
                            x-init="initializeMap()"
                        ></div>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Cliquez sur la carte ou déplacez le marqueur pour définir l'emplacement</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description avec animation -->
            <div class="mt-8" x-data="{ focused: false, filled: '{{ old('description', $site->description) }}' !== '' }">
                <label for="description" class="block text-sm font-medium text-indigo-600 mb-1">
                    Description <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        class="block w-full rounded-lg border-2 border-gray-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-all duration-300 resize-none @error('description') border-red-500 @enderror"
                        required
                        @focus="focused = true"
                        @blur="focused = document.getElementById('description').value !== ''"
                    >{{ old('description', $site->description) }}</textarea>
                    <div 
                        class="absolute bottom-0 left-0 h-1 bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-500"
                        :class="focused ? 'w-full' : 'w-0'"
                    ></div>
                </div>
                @error('description')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Boutons d'action -->
            <div class="mt-8 flex flex-wrap gap-4">
                <button
                    type="submit"
                    class="relative px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-lg shadow-md overflow-hidden group"
                >
                    <span class="relative z-10 flex items-center transition-transform group-hover:translate-x-1 duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Mettre à jour
                    </span>
                    <div class="absolute inset-0 h-full w-full scale-0 rounded-lg transition-all duration-300 group-hover:scale-100 group-hover:bg-white/10"></div>
                </button>
                
                <a
                    href="{{ route('admin.sites.index') }}"
                    class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors duration-300 flex items-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Annuler
                </a>
            </div>
        </form>
    </div>

    <!-- Images existantes -->
    @if($site->medias->count() > 0)
    <div class="bg-white rounded-xl shadow-lg mb-8 overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="bg-gradient-to-r from-purple-100 to-indigo-50 p-4 border-l-4 border-purple-500">
            <h2 class="text-lg font-semibold text-purple-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Images existantes
            </h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-data="{ activeImage: null }">
                @foreach($site->medias as $media)
                <div class="group relative rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <img 
                        src="{{ asset('storage/' . $media->url) }}" 
                        alt="Image du site" 
                        class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
                        @click="activeImage = '{{ asset('storage/' . $media->url) }}'"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-purple-900 to-transparent opacity-0 group-hover:opacity-70 transition-opacity duration-300"></div>
                    <form 
                        action="{{ route('admin.medias.destroy', $media) }}" 
                        method="POST" 
                        class="absolute bottom-2 left-0 right-0 flex justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    >
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="bg-red-500 text-white px-3 py-2 rounded-full hover:bg-red-600 shadow-lg flex items-center transition-transform duration-300 hover:scale-105"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette image?')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Supprimer
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            
            <!-- Modal de visualisation d'image -->
            <div 
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 transition-opacity"
                x-show="activeImage !== null" 
                x-transition
                @click.self="activeImage = null"
            >
                <div class="bg-white p-2 rounded-lg max-w-4xl max-h-3/4 overflow-auto relative">
                    <img :src="activeImage" alt="Prévisualisation" class="max-h-full">
                    <button 
                        class="absolute top-4 right-4 p-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors shadow-lg"
                        @click="activeImage = null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.10.3/dist/cdn.min.js" defer></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places" defer></script>

<script>
    function siteEditor() {
        return {
            map: null,
            marker: null,
            
            // Initialiser la carte Google Maps
            initializeMap() {
                // Attendre que l'API Google Maps soit chargée
                let checkGoogleMapsInterval = setInterval(() => {
                    if (window.google && window.google.maps) {
                        clearInterval(checkGoogleMapsInterval);
                        this.setupMap();
                    }
                }, 100);
            },
            
            setupMap() {
                // Récupérer les coordonnées existantes
                const initialLat = parseFloat(document.getElementById('latitude').value) || 6.428838;
                const initialLng = parseFloat(document.getElementById('longitude').value) || 2.341125;
                
                const initialPosition = {
                    lat: initialLat,
                    lng: initialLng
                };
                
                // Style personnalisé pour la carte
                const mapStyles = [
                    {
                                            "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [
                            { "color": "#e9e9e9" },
                            { "lightness": 17 }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "geometry",
                        "stylers": [
                            { "color": "#f5f5f5" },
                            { "lightness": 20 }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.fill",
                        "stylers": [
                            { "color": "#ffffff" },
                            { "lightness": 17 }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [
                            { "color": "#f5f5f5" },
                            { "lightness": 21 }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "geometry",
                        "stylers": [
                            { "color": "#f2f2f2" },
                            { "lightness": 19 }
                        ]
                    }
                ];
                
                // Options de la carte
                const mapOptions = {
                    center: initialPosition,
                    zoom: 14,
                    mapTypeControl: true,
                    streetViewControl: true,
                    mapTypeControlOptions: {
                        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                    },
                    styles: mapStyles
                };
                
                // Créer la carte
                this.map = new google.maps.Map(document.getElementById('map'), mapOptions);
                
                // Ajouter un marqueur
                this.marker = new google.maps.Marker({
                    position: initialPosition,
                    map: this.map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    title: 'Emplacement du site'
                });
                
                // Événement lors du clic sur la carte
                this.map.addListener('click', (event) => {
                    this.updateMarkerPosition(event.latLng);
                });
                
                // Événement lors du déplacement du marqueur
                this.marker.addListener('dragend', (event) => {
                    this.updateMarkerPosition(this.marker.getPosition());
                });
                
                // Configuration de l'autocomplétion pour le champ de localisation
                const input = document.getElementById('localisation');
                const autocomplete = new google.maps.places.Autocomplete(input);
                
                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    
                    if (!place.geometry) {
                        console.log("Aucun détail disponible pour cette entrée: '" + place.name + "'");
                        return;
                    }
                    
                    // Si l'endroit a une géométrie, alors le présenter sur la carte
                    if (place.geometry.viewport) {
                        this.map.fitBounds(place.geometry.viewport);
                    } else {
                        this.map.setCenter(place.geometry.location);
                        this.map.setZoom(17);
                    }
                    
                    // Mise à jour du marqueur
                    this.updateMarkerPosition(place.geometry.location);
                });
            },
            
            // Mettre à jour la position du marqueur et des champs de latitude/longitude
            updateMarkerPosition(location) {
                // Déplacer le marqueur
                this.marker.setPosition(location);
                
                // Mettre à jour les champs
                document.getElementById('latitude').value = location.lat();
                document.getElementById('longitude').value = location.lng();
                
                // Récupérer l'adresse correspondante
                this.getAddressFromCoordinates(location);
            },
            
            // Mettre à jour le marqueur depuis les champs de latitude/longitude
            updateMarkerFromInputs() {
                const lat = parseFloat(document.getElementById('latitude').value);
                const lng = parseFloat(document.getElementById('longitude').value);
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    const position = new google.maps.LatLng(lat, lng);
                    this.marker.setPosition(position);
                    this.map.setCenter(position);
                }
            },
            
            // Récupérer l'adresse à partir des coordonnées
            getAddressFromCoordinates(location) {
                const geocoder = new google.maps.Geocoder();
                
                geocoder.geocode({ 'location': location }, (results, status) => {
                    if (status === 'OK') {
                        if (results[0]) {
                            document.getElementById('localisation').value = results[0].formatted_address;
                        }
                    } else {
                        console.log('Géocodage inversé échoué pour la raison: ' + status);
                    }
                });
            },
            
            // Rechercher une localisation depuis le champ d'adresse
            searchLocation() {
                const address = document.getElementById('localisation').value;
                
                if (!address) return;
                
                const geocoder = new google.maps.Geocoder();
                
                geocoder.geocode({ 'address': address }, (results, status) => {
                    if (status === 'OK') {
                        this.map.setCenter(results[0].geometry.location);
                        this.updateMarkerPosition(results[0].geometry.location);
                    } else {
                        console.log('Géocodage échoué pour la raison: ' + status);
                    }
                });
            }
        };
    }
    
    // Fonction pour gérer l'aperçu des images
    function handlePreview(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('preview');
        
        // Effacer les prévisualisations existantes
        previewContainer.innerHTML = '';
        
        // Pour chaque fichier sélectionné
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            
            // Vérifier que c'est une image
            if (!file.type.startsWith('image/')) continue;
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'relative group rounded-lg overflow-hidden shadow-sm';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-24 object-cover transition-transform duration-300 group-hover:scale-110';
                img.alt = 'Prévisualisation';
                
                img.addEventListener('click', function() {
                    const previewModal = document.querySelector('[x-data]');
                    if (previewModal.__x) {
                        previewModal.__x.$data.previewOpen = true;
                        previewModal.__x.$data.previewImage = e.target.result;
                    }
                });
                
                const overlay = document.createElement('div');
                overlay.className = 'absolute inset-0 bg-gradient-to-t from-indigo-900 to-transparent opacity-0 group-hover:opacity-70 transition-opacity duration-300';
                
                previewItem.appendChild(img);
                previewItem.appendChild(overlay);
                previewContainer.appendChild(previewItem);
            };
            
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush