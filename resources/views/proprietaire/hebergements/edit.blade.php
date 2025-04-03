@extends('layouts.proprietaire')

@section('content')
<div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" 
x-data="{
    currentStep: 1,
    totalSteps: 4,
    imagePreviewUrls: [],
    existingImages: [],
    mapLoaded: false,
    map: null,
    marker: null,
    submitLoading: false,
    formData: {
        nom: '{{ old('nom', $hebergement->nom) }}',
        type_hebergement_id: '{{ old('type_hebergement_id', $hebergement->type_hebergement_id) }}',
        description: '{{ old('description', $hebergement->description) }}',
        adresse: '{{ old('adresse', $hebergement->adresse) }}',
        ville: '{{ old('ville', $hebergement->ville) }}',
        prix_min: '{{ old('prix_min', $hebergement->prix_min) }}',
        prix_max: '{{ old('prix_max', $hebergement->prix_max) }}',
        latitude: '{{ old('latitude', $hebergement->latitude) }}',
        longitude: '{{ old('longitude', $hebergement->longitude) }}',
        disponibilite: {{ old('disponibilite', $hebergement->disponibilite) ? 'true' : 'false' }}
    },
    init() {
        @foreach($hebergement->medias as $media)
            this.existingImages.push({
                id: {{ $media->id }},
                url: '{{ Storage::url($media->url) }}'
            });
        @endforeach
        this.$watch('formData.latitude', (value) => {
            if (this.map && value && this.formData.longitude) {
                this.updateMarker();
            }
        });
        this.$watch('formData.longitude', (value) => {
            if (this.map && value && this.formData.latitude) {
                this.updateMarker();
            }
        });
    },
    handleFileInput(event) {
        this.imagePreviewUrls = [];
        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreviewUrls.push(e.target.result);
            };
            reader.readAsDataURL(file);
        }
    },
    removeFile(index) {
        this.imagePreviewUrls.splice(index, 1);
        const input = document.getElementById('images');
        const newInput = document.createElement('input');
        newInput.type = 'file';
        newInput.id = 'images';
        newInput.name = 'images[]';
        newInput.multiple = true;
        newInput.className = input.className;
        newInput.accept = 'image/*';
        newInput.addEventListener('change', (event) => this.handleFileInput(event));
        input.parentNode.replaceChild(newInput, input);
    },
    confirmDeleteMedia(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
            // Récupérer le formulaire correspondant pour obtenir le token CSRF et l'URL
            const form = document.getElementById('delete-media-' + id);
            const url = form.action;
            const token = form.querySelector('input[name=\'_token\']').value;
            // Effectuer la requête AJAX
            fetch(url, {
                method: 'POST', // Laravel transforme DELETE en POST avec _method
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: new FormData(form) // Inclut _method=DELETE et _token
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors de la suppression');
                }
                return response.json();
            })
            .then(data => {
                // Supprimer l'image de la liste existante
                this.existingImages = this.existingImages.filter(image => image.id !== id);
                alert('Image supprimée avec succès !'); // Optionnel
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la suppression.');
            });
        }
    }, 
    nextStep() {
        if (this.currentStep < this.totalSteps) {
            this.currentStep++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
            if (this.currentStep === 3 && !this.mapLoaded) {
                this.$nextTick(() => {
                    this.initMap();
                });
            }
        }
    },
    prevStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },
    initMap() {
        if (typeof google !== 'undefined') {
            this.mapLoaded = true;
            const mapOptions = {
                center: { 
                    lat: this.formData.latitude ? parseFloat(this.formData.latitude) : 46.603354, 
                    lng: this.formData.longitude ? parseFloat(this.formData.longitude) : 1.888334 
                },
                zoom: this.formData.latitude ? 15 : 5,
                mapTypeControl: false,
                streetViewControl: false
            };
            this.map = new google.maps.Map(document.getElementById('map'), mapOptions);
            if (this.formData.latitude && this.formData.longitude) {
                this.marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(this.formData.latitude),
                        lng: parseFloat(this.formData.longitude)
                    },
                    map: this.map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                });
                google.maps.event.addListener(this.marker, 'dragend', (event) => {
                    this.formData.latitude = event.latLng.lat();
                    this.formData.longitude = event.latLng.lng();
                    this.geocodePosition(event.latLng);
                });
            }
            this.map.addListener('click', (event) => {
                this.placeMarker(event.latLng);
            });
            const input = document.getElementById('map-search');
            const searchBox = new google.maps.places.SearchBox(input);
            this.map.addListener('bounds_changed', () => {
                searchBox.setBounds(this.map.getBounds());
            });
            searchBox.addListener('places_changed', () => {
                const places = searchBox.getPlaces();
                if (places.length === 0) return;
                const place = places[0];
                if (!place.geometry || !place.geometry.location) return;
                if (place.geometry.viewport) {
                    this.map.fitBounds(place.geometry.viewport);
                } else {
                    this.map.setCenter(place.geometry.location);
                    this.map.setZoom(17);
                }
                this.placeMarker(place.geometry.location);
                if (place.formatted_address) {
                    this.formData.adresse = place.formatted_address;
                    for (const component of place.address_components) {
                        if (component.types.includes('locality')) {
                            this.formData.ville = component.long_name;
                            break;
                        }
                    }
                }
            });
        }
    },
    placeMarker(location) {
        if (this.marker) {
            this.marker.setPosition(location);
        } else {
            this.marker = new google.maps.Marker({
                position: location,
                map: this.map,
                draggable: true,
                animation: google.maps.Animation.DROP
            });
            google.maps.event.addListener(this.marker, 'dragend', (event) => {
                this.formData.latitude = event.latLng.lat();
                this.formData.longitude = event.latLng.lng();
                this.geocodePosition(event.latLng);
            });
        }
        this.formData.latitude = location.lat();
        this.formData.longitude = location.lng();
        this.geocodePosition(location);
    },
    updateMarker() {
        const position = {
            lat: parseFloat(this.formData.latitude),
            lng: parseFloat(this.formData.longitude)
        };
        if (this.marker) {
            this.marker.setPosition(position);
        } else {
            this.marker = new google.maps.Marker({
                position: position,
                map: this.map,
                draggable: true,
                animation: google.maps.Animation.DROP
            });
            google.maps.event.addListener(this.marker, 'dragend', (event) => {
                this.formData.latitude = event.latLng.lat();
                this.formData.longitude = event.latLng.lng();
                this.geocodePosition(event.latLng);
            });
        }
        this.map.panTo(position);
        this.map.setZoom(15);
    },
    geocodePosition(latLng) {
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: latLng }, (results, status) => {
            if (status === 'OK' && results[0]) {
                this.formData.adresse = results[0].formatted_address;
                for (const component of results[0].address_components) {
                    if (component.types.includes('locality')) {
                        this.formData.ville = component.long_name;
                        break;
                    }
                }
            }
        });
    },
    submitForm() {
        this.submitLoading = true;
        document.getElementById('update-form').submit();
    }
}"
     x-init="$nextTick(() => {
        // Charger l'API Google Maps seulement quand c'est nécessaire
        window.initGoogleMaps = () => {}
     })">
    
    <!-- Formulaires de suppression d'image en dehors du formulaire principal -->
    @foreach($hebergement->medias as $media)
        <form id="delete-media-{{ $media->id }}" action="{{ route('proprietaire.hebergements.delete-media', [$hebergement, $media]) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
    
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <span class="bg-blue-600 text-white p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </span>
                Modifier l'hébergement
            </h1>
            <p class="mt-2 text-lg text-gray-600 italic">
                "{{ $hebergement->nom }}"
            </p>
        </div>
        <a href="{{ route('proprietaire.hebergements.index') }}" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>
    
    <!-- Barre de progression -->
    <div class="w-full bg-gray-100 h-2 rounded-full mb-8">
        <div class="bg-blue-600 h-2 rounded-full transition-all duration-500 ease-in-out" 
             :style="`width: ${currentStep / totalSteps * 100}%`"></div>
    </div>
    
    <!-- Affichage des étapes -->
    <div class="flex justify-center mb-8">
        <div class="grid grid-cols-4 gap-4 w-full max-w-3xl">
            <template x-for="step in totalSteps" :key="step">
                <div class="flex flex-col items-center">
                    <div :class="`h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-medium transition-all duration-300 ${currentStep >= step ? 'bg-blue-600 shadow-lg scale-105' : 'bg-gray-300'}`">
                        <span x-text="step"></span>
                    </div>
                    <div class="mt-2 text-xs font-medium text-center" :class="currentStep >= step ? 'text-blue-600' : 'text-gray-500'">
                        <span x-show="step === 1">Informations</span>
                        <span x-show="step === 2">Description</span>
                        <span x-show="step === 3">Localisation</span>
                        <span x-show="step === 4">Photos</span>
                    </div>
                </div>
            </template>
        </div>
    </div>
    
    <!-- Messages d'erreur et de succès -->
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-md animate-fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Certaines informations sont incorrectes :</h3>
                    <ul class="mt-2 list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md animate-fade-in">
            <div class="flex justify-between items-center">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-800">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-xl overflow-hidden transition-all duration-500">
        <form id="update-form" action="{{ route('proprietaire.hebergements.update', $hebergement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Étape 1: Informations générales -->
            <div x-show="currentStep === 1" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-40"
                 x-transition:enter-end="opacity-100 transform translate-x-0">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informations générales
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'hébergement</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <input type="text" name="nom" id="nom" x-model="formData.nom" required 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg"
                                       placeholder="ex: Villa sur la plage">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="type_hebergement_id" class="block text-sm font-medium text-gray-700 mb-1">Type d'hébergement</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <select id="type_hebergement_id" name="type_hebergement_id" x-model="formData.type_hebergement_id" required 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg">
                                    <option value="">Sélectionner un type</option>
                                    @foreach($typesHebergement as $type)
                                        <option value="{{ $type->id }}">{{ $type->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="prix_min" class="block text-sm font-medium text-gray-700 mb-1">Prix minimum (€)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input type="number" min="0" step="0.01" name="prix_min" id="prix_min" x-model="formData.prix_min" required 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg"
                                       placeholder="ex: 50">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="prix_max" class="block text-sm font-medium text-gray-700 mb-1">Prix maximum (€)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input type="number" min="0" step="0.01" name="prix_max" id="prix_max" x-model="formData.prix_max" required 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg"
                                       placeholder="ex: 200">
                            </div>
                        </div>
                        
                        <div class="sm:col-span-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="disponibilite" name="disponibilite" type="checkbox" 
                                           x-model="formData.disponibilite"
                                           class="focus:ring-blue-500 h-5 w-5 text-blue-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3">
                                    <label for="disponibilite" class="font-medium text-gray-700 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Disponible à la réservation
                                    </label>
                                    <p class="text-gray-500 text-sm">Cochez cette case si l'hébergement est actuellement disponible pour les réservations.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Étape 2: Description -->
            <div x-show="currentStep === 2"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-40"
                 x-transition:enter-end="opacity-100 transform translate-x-0">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Description détaillée
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description de votre hébergement</label>
                        <div class="relative">
                            <textarea id="description" name="description" rows="8" x-model="formData.description" required 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-lg transition-all duration-200"
                                    placeholder="Décrivez votre hébergement en détail, ses caractéristiques, ses atouts et son environnement..."></textarea>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-sm text-gray-500">
                                <span class="font-medium">Astuce :</span> Une description détaillée augmente vos chances de réservation.
                            </p>
                            <p class="text-sm text-gray-500" x-text="`${formData.description.length} caractères`"></p>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                    <h4 class="text-sm font-medium text-blue-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Conseils pour une bonne description
                        </h4>
                        <ul class="mt-2 text-sm text-blue-700 space-y-1">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Décrivez les pièces, la vue, l'environnement et les équipements
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Mentionnez les attractions et activités à proximité
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Précisez les règles spécifiques si nécessaire
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Étape 3: Localisation -->
            <div x-show="currentStep === 3"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-40"
                 x-transition:enter-end="opacity-100 transform translate-x-0">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Localisation
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="mb-6">
                        <label for="map-search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher un lieu</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="map-search" 
                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg"
                                   placeholder="Entrez une adresse ou un lieu">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Recherchez une adresse ou cliquez directement sur la carte pour positionner votre hébergement
                        </p>
                    </div>

                    <div class="mb-6">
                        <div id="map" class="h-80 w-full rounded-lg shadow-md border border-gray-200"></div>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="adresse" id="adresse" x-model="formData.adresse" required 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg"
                                       placeholder="ex: 123 rue de la Paix">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <input type="text" name="ville" id="ville" x-model="formData.ville" required 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg"
                                       placeholder="ex: Paris">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <input type="text" name="latitude" id="latitude" x-model="formData.latitude" required 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg"
                                       placeholder="ex: 48.8566">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                    </svg>
                                </div>
                                <input type="text" name="longitude" id="longitude" x-model="formData.longitude" required 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg"
                                       placeholder="ex: 2.3522">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Étape 4: Photos -->
            <div x-show="currentStep === 4"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-40"
                 x-transition:enter-end="opacity-100 transform translate-x-0">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Photos
                    </h3>
                </div>
                
                <div class="p-6">
                    <!-- Photos existantes -->
                    <div x-show="existingImages.length > 0" class="mb-6">
                        <h4 class="text-lg font-medium text-gray-800 mb-3">Photos actuelles</h4>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                            <template x-for="(image, index) in existingImages" :key="image.id">
                                <div class="relative group">
                                    <div class="aspect-w-3 aspect-h-2 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                        <img :src="image.url" :alt="`Photo ${index + 1}`" class="object-cover transform group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end">
                                            <div class="p-3 w-full">
                                                <button type="button" @click="confirmDeleteMedia(image.id)" 
                                                        class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Ajout de nouvelles photos -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-800 mb-3">Ajouter de nouvelles photos</h4>
                        <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors duration-300">
                            <div class="space-y-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="images" class="relative cursor-pointer bg-blue-600 py-2 px-4 rounded-md font-medium text-white hover:bg-blue-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 transition-colors duration-200">
                                        <span>Choisir des fichiers</span>
                                        <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" @change="handleFileInput">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 10MB</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Prévisualisation des nouvelles photos -->
                    <div x-show="imagePreviewUrls.length > 0" class="mb-6">
                        <h4 class="text-lg font-medium text-gray-800 mb-3">Prévisualisation</h4>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                            <template x-for="(url, index) in imagePreviewUrls" :key="index">
                                <div class="relative group">
                                    <div class="aspect-w-3 aspect-h-2 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                        <img :src="url" :alt="`Nouvelle photo ${index + 1}`" class="object-cover transform group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end">
                                            <div class="p-3 w-full">
                                                <button type="button" @click="removeFile(index)" 
                                                        class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100 mb-6">
                        <h4 class="text-sm font-medium text-yellow-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Conseils pour de bonnes photos
                        </h4>
                        <ul class="mt-2 text-sm text-yellow-700 space-y-1">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Utilisez des photos de haute qualité et bien éclairées
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Montrez chaque pièce et les extérieurs
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Mettez en valeur les caractéristiques uniques de votre hébergement
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Boutons de navigation -->
            <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
                <button type="button" @click="prevStep" x-show="currentStep > 1"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Précédent
                </button>
                
                <div x-show="currentStep < totalSteps">
                    <button type="button" @click="nextStep"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Suivant
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                
                <div x-show="currentStep === totalSteps">
                    <button type="button" @click="submitForm" :disabled="submitLoading"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 disabled:opacity-50">
                        <template x-if="!submitLoading">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="submitLoading">
                            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        Enregistrer les modifications
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script pour Google Maps -->
<script>
    function initGoogleMaps() {
        const event = new CustomEvent('google-maps-loaded');
        window.dispatchEvent(event);
    }
    
    document.addEventListener('alpine:init', () => {
        window.addEventListener('google-maps-loaded', () => {
            if (document.getElementById('map')) {
                Alpine.store('mapLoaded', true);
                
                // Initialiser la carte si l'utilisateur est déjà à l'étape de localisation
                const currentStep = document.querySelector('[x-data]').__x.$data.currentStep;
                if (currentStep === 3) {
                    document.querySelector('[x-data]').__x.$data.initMap();
                }
            }
        });
    });
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initGoogleMaps" async defer></script>
@endsection