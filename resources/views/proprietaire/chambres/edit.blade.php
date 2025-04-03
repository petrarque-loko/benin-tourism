@extends('layouts.proprietaire')

@section('title', 'Modifier une chambre')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="chambreForm()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Modifier la chambre</h1>
        <a href="{{ route('proprietaire.hebergements.chambres.index', $hebergement->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition duration-200">Retour</a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('proprietaire.hebergements.chambres.update', ['hebergement' => $hebergement->id, 'chambre' => $chambre->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Numéro de chambre -->
                <div>
                    <label for="numero" class="block mb-2 font-medium text-gray-700">Numéro (optionnel)</label>
                    <input type="text" name="numero" id="numero" value="{{ old('numero', $chambre->numero) }}" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('numero')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nom de la chambre -->
                <div>
                    <label for="nom" class="block mb-2 font-medium text-gray-700">Nom de la chambre *</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom', $chambre->nom) }}" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    @error('nom')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type de chambre -->
                <div>
                    <label for="type_chambre" class="block mb-2 font-medium text-gray-700">Type de chambre *</label>
                    <select name="type_chambre" id="type_chambre" 
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="simple" {{ old('type_chambre', $chambre->type_chambre) == 'simple' ? 'selected' : '' }}>Simple</option>
                        <option value="double" {{ old('type_chambre', $chambre->type_chambre) == 'double' ? 'selected' : '' }}>Double</option>
                        <option value="twin" {{ old('type_chambre', $chambre->type_chambre) == 'twin' ? 'selected' : '' }}>Twin</option>
                        <option value="triple" {{ old('type_chambre', $chambre->type_chambre) == 'triple' ? 'selected' : '' }}>Triple</option>
                        <option value="suite" {{ old('type_chambre', $chambre->type_chambre) == 'suite' ? 'selected' : '' }}>Suite</option>
                    </select>
                    @error('type_chambre')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacité -->
                <div>
                    <label for="capacite" class="block mb-2 font-medium text-gray-700">Capacité (nombre de personnes) *</label>
                    <input type="number" name="capacite" id="capacite" value="{{ old('capacite', $chambre->capacite) }}" min="1" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    @error('capacite')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prix -->
                <div>
                    <label for="prix" class="block mb-2 font-medium text-gray-700">Prix par nuit (€) *</label>
                    <input type="number" name="prix" id="prix" value="{{ old('prix', $chambre->prix) }}" min="0" step="0.01" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    @error('prix')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block mb-2 font-medium text-gray-700">Description *</label>
                <textarea name="description" id="description" rows="5" 
                          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>{{ old('description', $chambre->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options de visibilité et disponibilité -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center">
                    <input type="checkbox" name="est_visible" id="est_visible" class="w-5 h-5 text-blue-600 mr-2" 
                           {{ old('est_visible', $chambre->est_visible) ? 'checked' : '' }}>
                    <label for="est_visible" class="text-gray-700">Visible sur le site</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="est_disponible" id="est_disponible" class="w-5 h-5 text-blue-600 mr-2" 
                           {{ old('est_disponible', $chambre->est_disponible) ? 'checked' : '' }}>
                    <label for="est_disponible" class="text-gray-700">Disponible à la réservation</label>
                </div>
            </div>

            <!-- Équipements -->
            <div class="mt-6">
                <h3 class="font-medium mb-3 text-gray-700">Équipements</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($equipements as $equipement)
                        <div class="flex items-center">
                            <input type="checkbox" name="equipements[]" id="equipement_{{ $equipement->id }}" value="{{ $equipement->id }}" 
                                   {{ in_array($equipement->id, old('equipements', $chambreEquipements)) ? 'checked' : '' }} 
                                   class="w-5 h-5 text-blue-600 mr-2">
                            <label for="equipement_{{ $equipement->id }}" class="text-gray-700">{{ $equipement->nom }}</label>
                        </div>
                    @endforeach
                </div>
                @error('equipements')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Images actuelles -->
            @if(isset($chambre) && $chambre->medias->count() > 0)
                <div class="mt-6" x-data="{ 
                    existingImages: {{ json_encode($chambre->medias->map(function($media) use ($chambre) { 
                        return [
                            'id' => $media->id, 
                            'url' => asset('storage/' . $media->url), 
                            'name' => $chambre->nom
                        ]; 
                    })) }} 
                }">
                    <h3 class="font-medium mb-3 text-gray-700">Images actuelles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="(media, index) in existingImages" :key="media.id">
                            <div class="relative group">
                                <img :src="media.url" :alt="media.name" class="w-full h-48 object-cover rounded">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100">
                                    <button type="button" @click="confirmDeleteMedia(media.id)" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Formulaire caché pour la suppression -->
                                <form :id="'delete-media-' + media.id" method="POST" class="hidden" :action="'{{ url('proprietaire/hebergements/'.$hebergement->id.'/chambres/'.$chambre->id.'/medias/') }}/' + media.id">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </template>
                    </div>
                </div>
            @endif

            <!-- Nouvelles images -->
            <div class="mt-6">
                <label for="images" class="block mb-2 font-medium text-gray-700">Ajouter des images</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center" 
                     x-data="imageUploader()"
                     x-on:dragover.prevent="dragOver = true"
                     x-on:dragleave.prevent="dragOver = false"
                     x-on:drop.prevent="dropHandler($event)">
                    
                    <div x-show="!previewImages.length" class="space-y-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-500">Glissez et déposez des images ici ou</p>
                        <button type="button" @click="document.getElementById('images').click()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-block transition duration-200">
                            Parcourir
                        </button>
                    </div>
                    
                    <div x-show="previewImages.length" class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <template x-for="(image, index) in previewImages" :key="index">
                            <div class="relative group">
                                <img :src="image.preview" class="w-full h-48 object-cover rounded">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100">
                                    <button type="button" @click="removeImage(index)" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <input type="file" name="images[]" id="images" multiple class="hidden" @change="handleFileSelect($event)">
                    <p class="text-gray-500 text-sm mt-3">Formats acceptés: jpg, jpeg, png, gif. Taille max: 2Mo</p>
                </div>
                @error('images.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition duration-200 flex items-center">
                    <span>Mettre à jour</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Toast de notification -->
    <div x-show="notification.show" @click="notification.show = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3">
        <span x-text="notification.message"></span>
        <button @click="notification.show = false" class="text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>

<script>
    function chambreForm() {
        return {
            notification: {
                show: false,
                message: ''
            },
            
            confirmDeleteMedia(id) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
                    // Récupérer le formulaire correspondant
                    const form = document.getElementById('delete-media-' + id);
                    const url = form.action;
                    
                    // Créer un FormData avec la méthode DELETE explicite
                    const formData = new FormData(form);
                    formData.append('_method', 'DELETE');
                    
                    // Référence à "this" pour l'utiliser dans les callbacks
                    const self = this;
                    
                    // Effectuer la requête AJAX
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur lors de la suppression');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Modification ici: au lieu d'accéder à __x.$data, utilisez Alpine.store ou une approche différente
                            // Supprimer l'élément du DOM ou rafraîchir la page
                            const mediaElement = form.closest('.group');
                            if (mediaElement) {
                                mediaElement.remove();
                            }
                            
                            // Afficher la notification
                            self.notification.message = data.message || 'Image supprimée avec succès';
                            self.notification.show = true;
                            setTimeout(() => {
                                self.notification.show = false;
                            }, 3000);
                        } else {
                            throw new Error(data.message || 'Erreur inconnue');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        self.notification.message = error.message || 'Une erreur est survenue';
                        self.notification.show = true;
                    });
                }
            }
        }
    }

    function imageUploader() {
        return {
            dragOver: false,
            previewImages: [],
            
            handleFileSelect(event) {
                this.addFiles(event.target.files);
            },
            
            dropHandler(event) {
                this.dragOver = false;
                this.addFiles(event.dataTransfer.files);
            },
            
            addFiles(files) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                const maxSize = 2 * 1024 * 1024; // 2Mo

                for (let file of files) {
                    // Validation du type
                    if (!allowedTypes.includes(file.type)) {
                        alert(`Le fichier ${file.name} n'est pas une image valide (jpg, png, gif uniquement).`);
                        continue;
                    }
                    
                    // Validation de la taille
                    if (file.size > maxSize) {
                        alert(`Le fichier ${file.name} dépasse la taille maximale de 2Mo.`);
                        continue;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previewImages.push({
                            file: file,
                            preview: e.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                }
            },
            
            removeImage(index) {
                this.previewImages.splice(index, 1);
                
                let dt = new DataTransfer();
                let input = document.getElementById('images');
                
                for (let i = 0; i < this.previewImages.length; i++) {
                    dt.items.add(this.previewImages[i].file);
                }
                
                input.files = dt.files;
            }
        }
    }
</script>
@endsection