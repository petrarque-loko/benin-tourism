@extends('layouts.proprietaire')

@section('content')
<div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête avec animation d'apparition -->
    <div class="mb-8 transform transition-all duration-500 translate-y-0 opacity-100" 
         x-data="{ show: false }" 
         x-init="setTimeout(() => show = true, 100)" 
         :class="{ 'translate-y-4 opacity-0': !show, 'translate-y-0 opacity-100': show }">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Ajouter un hébergement
        </h1>
        <p class="mt-2 text-lg text-gray-600 max-w-3xl">
            Créez un nouvel hébergement à proposer aux voyageurs. Remplissez tous les détails pour attirer plus de réservations.
        </p>
        <div class="h-1 w-32 bg-blue-600 mt-4 rounded-full"></div>
    </div>

    <!-- Formulaire principal avec onglets -->
    <div class="bg-white shadow-xl rounded-xl overflow-hidden" x-data="{
        currentStep: 1,
        totalSteps: 3,
        imagePreviewUrls: [],
        map: null,
        marker: null,
        latitude: '{{ old('latitude') }}',
        longitude: '{{ old('longitude') }}',
        validForm: false,
        formData: {
            nom: '{{ old('nom') }}',
            type_hebergement_id: '{{ old('type_hebergement_id') }}',
            description: '{{ old('description') }}',
            adresse: '{{ old('adresse') }}',
            ville: '{{ old('ville') }}',
            prix_min: '{{ old('prix_min') }}',
            prix_max: '{{ old('prix_max') }}',
        },
        
        initMap() {
            if (document.getElementById('map')) {
                const defaultLat = this.latitude ? parseFloat(this.latitude) : 46.603354;
                const defaultLng = this.longitude ? parseFloat(this.longitude) : 1.888334;
                
                this.map = new google.maps.Map(document.getElementById('map'), {
                    center: { lat: defaultLat, lng: defaultLng },
                    zoom: this.latitude ? 15 : 5,
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: true
                });
                
                this.marker = new google.maps.Marker({
                    position: { lat: defaultLat, lng: defaultLng },
                    map: this.map,
                    draggable: true,
                    animation: google.maps.Animation.DROP
                });
                
                if (!this.latitude) {
                    this.marker.setVisible(false);
                }
                
                google.maps.event.addListener(this.marker, 'dragend', () => {
                    const position = this.marker.getPosition();
                    this.latitude = position.lat();
                    this.longitude = position.lng();
                    document.getElementById('latitude').value = this.latitude;
                    document.getElementById('longitude').value = this.longitude;
                    
                    // Obtenir l'adresse à partir des coordonnées
                    this.getAddressFromCoordinates(this.latitude, this.longitude);
                });
                
                // Ajouter un écouteur de clic sur la carte
                google.maps.event.addListener(this.map, 'click', (event) => {
                    const clickedLocation = event.latLng;
                    this.marker.setPosition(clickedLocation);
                    this.marker.setVisible(true);
                    this.marker.setAnimation(google.maps.Animation.DROP);
                    
                    this.latitude = clickedLocation.lat();
                    this.longitude = clickedLocation.lng();
                    document.getElementById('latitude').value = this.latitude;
                    document.getElementById('longitude').value = this.longitude;
                    
                    // Obtenir l'adresse à partir des coordonnées
                    this.getAddressFromCoordinates(this.latitude, this.longitude);
                });
                
                // Ajouter la recherche d'adresse
                const searchInput = document.getElementById('search-address');
                const searchBox = new google.maps.places.SearchBox(searchInput);
                
                searchBox.addListener('places_changed', () => {
                    const places = searchBox.getPlaces();
                    
                    if (places.length === 0) {
                        return;
                    }
                    
                    const place = places[0];
                    
                    if (!place.geometry || !place.geometry.location) {
                        return;
                    }
                    
                    // Mettre à jour la carte et le marqueur
                    this.map.setCenter(place.geometry.location);
                    this.map.setZoom(15);
                    this.marker.setPosition(place.geometry.location);
                    this.marker.setVisible(true);
                    
                    // Mettre à jour les coordonnées
                    this.latitude = place.geometry.location.lat();
                    this.longitude = place.geometry.location.lng();
                    document.getElementById('latitude').value = this.latitude;
                    document.getElementById('longitude').value = this.longitude;
                    
                    // Mettre à jour l'adresse et la ville
                    if (place.formatted_address) {
                        document.getElementById('adresse').value = place.formatted_address;
                        this.formData.adresse = place.formatted_address;
                    }
                    
                    // Extraire la ville
                    const cityComponent = place.address_components.find(component => 
                        component.types.includes('locality') || 
                        component.types.includes('administrative_area_level_1')
                    );
                    
                    if (cityComponent) {
                        document.getElementById('ville').value = cityComponent.long_name;
                        this.formData.ville = cityComponent.long_name;
                    }
                });
            }
        },
        
        getAddressFromCoordinates(lat, lng) {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: { lat, lng } }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    document.getElementById('adresse').value = results[0].formatted_address;
                    this.formData.adresse = results[0].formatted_address;
                    
                    // Extraire la ville
                    const cityComponent = results[0].address_components.find(component => 
                        component.types.includes('locality') || 
                        component.types.includes('administrative_area_level_1')
                    );
                    
                    if (cityComponent) {
                        document.getElementById('ville').value = cityComponent.long_name;
                        this.formData.ville = cityComponent.long_name;
                    }
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
                    this.imagePreviewUrls.push({
                        url: e.target.result,
                        name: file.name,
                        size: this.formatFileSize(file.size)
                    });
                };
                
                reader.readAsDataURL(file);
            }
        },
        
        formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' octets';
            else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' Ko';
            else return (bytes / 1048576).toFixed(1) + ' Mo';
        },
        
        removeFile(index) {
            this.imagePreviewUrls.splice(index, 1);
            
            // Recréer l'input file pour refléter les changements
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
        
        nextStep() {
            if (this.currentStep < this.totalSteps) {
                this.currentStep++;
            }
        },
        
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        
        validateStep1() {
            return this.formData.nom && 
                   this.formData.type_hebergement_id && 
                   this.formData.description && 
                   this.formData.description.length >= 50;
        },
        
        validateStep2() {
            return this.formData.adresse && 
                   this.formData.ville && 
                   this.latitude && 
                   this.longitude;
        },
        
        validateForm() {
            this.validForm = this.validateStep1() && 
                           this.validateStep2() && 
                           this.formData.prix_min && 
                           this.formData.prix_max && 
                           parseFloat(this.formData.prix_min) <= parseFloat(this.formData.prix_max);
        }
    }"
    x-init="$nextTick(() => { 
        if (typeof google === 'undefined') {
            window.initMapCallback = () => {
                initMap();
                validateForm();
                $watch('formData', () => validateForm());
                $watch('latitude', () => validateForm());
                $watch('longitude', () => validateForm());
            };
        } else {
            initMap();
            validateForm();
            $watch('formData', () => validateForm());
            $watch('latitude', () => validateForm());
            $watch('longitude', () => validateForm());
        }
    })">
        <form action="{{ route('proprietaire.hebergements.store') }}" method="POST" enctype="multipart/form-data" class="relative">
            @csrf
            
            <!-- Progress Bar -->
            <div class="w-full bg-gray-100 h-1.5">
                <div class="bg-blue-600 h-1.5 transition-all duration-300 ease-out" :style="`width: ${(currentStep / totalSteps) * 100}%`"></div>
            </div>
            
            <!-- Navigation des étapes -->
            <div class="flex justify-between border-b border-gray-200 px-8 py-4">
                <div class="flex space-x-8">
                    <button type="button" @click="currentStep = 1" :class="{'text-blue-600 border-b-2 border-blue-600 -mb-4 pb-4 font-medium': currentStep === 1, 'text-gray-500 hover:text-gray-700': currentStep !== 1}" class="transition-all duration-200">
                        <div class="flex items-center">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full mr-2 text-white" :class="{'bg-blue-600': currentStep >= 1, 'bg-gray-300': currentStep < 1}">1</span>
                            Informations
                        </div>
                    </button>
                    
                    <button type="button" @click="currentStep = 2" :class="{'text-blue-600 border-b-2 border-blue-600 -mb-4 pb-4 font-medium': currentStep === 2, 'text-gray-500 hover:text-gray-700': currentStep !== 2}" class="transition-all duration-200">
                        <div class="flex items-center">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full mr-2 text-white" :class="{'bg-blue-600': currentStep >= 2, 'bg-gray-300': currentStep < 2}">2</span>
                            Localisation
                        </div>
                    </button>
                    
                    <button type="button" @click="currentStep = 3" :class="{'text-blue-600 border-b-2 border-blue-600 -mb-4 pb-4 font-medium': currentStep === 3, 'text-gray-500 hover:text-gray-700': currentStep !== 3}" class="transition-all duration-200">
                        <div class="flex items-center">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full mr-2 text-white" :class="{'bg-blue-600': currentStep >= 3, 'bg-gray-300': currentStep < 3}">3</span>
                            Médias & Prix
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Affichage des erreurs -->
            @if($errors->any())
                <div class="px-4 py-3 mx-6 mt-6 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-md animate-pulse">
                    <h4 class="text-lg font-medium mb-2">Veuillez corriger les erreurs suivantes :</h4>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Étape 1: Informations générales -->
            <div x-show="currentStep === 1" class="px-6 py-6 transition-all duration-300 ease-in-out" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-x-12" x-transition:enter-end="opacity-100 transform translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform translate-x-12">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path d="M12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Informations générales</h3>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Donnez un nom accrocheur et une description détaillée pour attirer les voyageurs.</p>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'hébergement</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" x-model="formData.nom" required class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md transition-all duration-200" placeholder="ex: Villa avec vue sur la mer">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="type_hebergement_id" class="block text-sm font-medium text-gray-700 mb-1">Type d'hébergement</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <select id="type_hebergement_id" name="type_hebergement_id" x-model="formData.type_hebergement_id" required class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md transition-all duration-200">
                                <option value="">Sélectionner...</option>
                                @foreach($typesHebergement as $type)
                                    <option value="{{ $type->id }}" {{ old('type_hebergement_id') == $type->id ? 'selected' : '' }}>{{ $type->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description détaillée</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <textarea id="description" name="description" rows="6" x-model="formData.description" required class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md transition-all duration-200" placeholder="Décrivez en détail votre hébergement, ses caractéristiques, son environnement et ce qui le rend unique...">{{ old('description') }}</textarea>
                            <div class="absolute bottom-2 right-2 text-xs text-gray-500" x-text="formData.description ? formData.description.length + ' caractères' : '0 caractère'"></div>
                        </div>
                        <div class="mt-2 flex items-center" x-show="formData.description">
                            <div class="h-1 flex-grow rounded-full bg-gray-200 overflow-hidden">
                                <div class="h-full transition-all duration-300 ease-out rounded-full" :class="{ 
                                    'bg-red-500': formData.description && formData.description.length < 50,
                                    'bg-yellow-500': formData.description && formData.description.length >= 50 && formData.description.length < 100,
                                    'bg-green-500': formData.description && formData.description.length >= 100
                                }" :style="`width: ${Math.min(formData.description.length / 2, 100)}%`"></div>
                            </div>
                            <span class="ml-2 text-xs" :class="{
                                'text-red-500': formData.description && formData.description.length < 50,
                                'text-yellow-500': formData.description && formData.description.length >= 50 && formData.description.length < 100,
                                'text-green-500': formData.description && formData.description.length >= 100
                            }">
                                <span x-show="formData.description.length < 50">Description trop courte</span>
                                <span x-show="formData.description.length >= 50 && formData.description.length < 100">Description correcte</span>
                                <span x-show="formData.description.length >= 100">Description détaillée</span>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end">
                    <button type="button" @click="nextStep()" :disabled="!validateStep1()" :class="{'opacity-50 cursor-not-allowed': !validateStep1(), 'hover:bg-blue-700': validateStep1()}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        Suivant
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Étape 2: Localisation -->
            <div x-show="currentStep === 2" class="px-6 py-6 transition-all duration-300 ease-in-out" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-12" x-transition:enter-end="opacity-100 transform translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform -translate-x-12">
                <div class="sm:col-span-6 mb-6">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Localisation de l'hébergement</h3>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Sélectionnez l'emplacement sur la carte ou recherchez une adresse.</p>
                </div>
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="search-address" placeholder="Rechercher une adresse..." class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md transition-all duration-200">
                        </div>
                    </div>
                    
                    <div class="sm:col-span-6">
                        <div id="map" class="w-full h-96 rounded-lg shadow-md border border-gray-200 transition-all duration-200"></div>
                    </div>
                    
                    <div class="sm:col-span-4">
                        <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1113.314-3.314 8 8 0 01-2 8.314z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}" x-model="formData.adresse" required class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md transition-all duration-200">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <input type="text" name="ville" id="ville" value="{{ old('ville') }}" x-model="formData.ville" required class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md transition-all duration-200">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" step="any" name="latitude" id="latitude" x-model="latitude" class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md transition-all duration-200" readonly>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" step="any" name="longitude" id="longitude" x-model="longitude" class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md transition-all duration-200" readonly>
                        </div>
                        </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button type="button" @click="prevStep()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Précédent
                </button>
                
                <button type="button" @click="nextStep()" :disabled="!validateStep2()" :class="{'opacity-50 cursor-not-allowed': !validateStep2(), 'hover:bg-blue-700': validateStep2()}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    Suivant
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Étape 3: Médias & Prix -->
        <div x-show="currentStep === 3" class="px-6 py-6 transition-all duration-300 ease-in-out" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-12" x-transition:enter-end="opacity-100 transform translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform translate-x-12">
            <div class="sm:col-span-6 mb-6">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Médias et tarification</h3>
                </div>
                <p class="mt-1 text-sm text-gray-500">Ajoutez des photos attractives et définissez vos tarifs.</p>
            </div>
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="prix_min" class="block text-sm font-medium text-gray-700 mb-1">Prix minimum (€ par nuit)</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">€</span>
                        </div>
                        <input type="number" min="0" step="0.01" name="prix_min" id="prix_min" value="{{ old('prix_min') }}" x-model="formData.prix_min" required class="pl-7 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md transition-all duration-200">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="prix_max" class="block text-sm font-medium text-gray-700 mb-1">Prix maximum (€ par nuit)</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">€</span>
                        </div>
                        <input type="number" min="0" step="0.01" name="prix_max" id="prix_max" value="{{ old('prix_max') }}" x-model="formData.prix_max" required class="pl-7 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md transition-all duration-200">
                    </div>
                </div>
                
                <div class="sm:col-span-6">
                    <div class="flex justify-between items-center">
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Photos de l'hébergement</label>
                        <span class="text-xs text-gray-500">Max 5 images, 2MB par image</span>
                    </div>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md transition-all duration-200 hover:border-blue-400">
                        <div class="space-y-1 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Télécharger des fichiers</span>
                                    <input id="images" name="images[]" type="file" multiple accept="image/*" @change="handleFileInput($event)" class="sr-only">
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2MB</p>
                        </div>
                    </div>
                </div>
                
                <!-- Aperçu des images -->
                <div class="sm:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-show="imagePreviewUrls.length > 0">
                        <template x-for="(preview, index) in imagePreviewUrls" :key="index">
                            <div class="relative group overflow-hidden rounded-md shadow-md transition-all duration-200 hover:shadow-lg">
                                <img :src="preview.url" class="h-40 w-full object-cover">
                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 truncate" x-text="preview.name + ' - ' + preview.size"></div>
                                <button type="button" @click="removeFile(index)" class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
                
                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="disponibilite" name="disponibilite" type="checkbox" checked class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded transition-all duration-200">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="disponibilite" class="font-medium text-gray-700">Disponible immédiatement</label>
                            <p class="text-gray-500">Rendre cet hébergement visible et réservable dès maintenant.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button type="button" @click="prevStep()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Précédent
                </button>
                
                <button type="submit" :disabled="!validForm" :class="{'opacity-50 cursor-not-allowed': !validForm, 'hover:bg-green-700': validForm}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Créer l'hébergement
                </button>
            </div>
        </div>
        
        <!-- Champs cachés pour les coordonnées -->
        <input type="hidden" name="latitude" x-bind:value="latitude">
        <input type="hidden" name="longitude" x-bind:value="longitude">
    </form>
</div>

<!-- Script pour charger Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places" defer></script>
@endsection