@extends('layouts.proprietaire')

@section('title', 'Ajouter une chambre')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Ajouter une chambre</h1>
            <a href="{{ route('proprietaire.hebergements.chambres.index', $hebergement->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-medium text-gray-700">Hébergement : {{ $hebergement->nom }}</h2>
                    <p class="text-sm text-gray-500">{{ $hebergement->adresse }}, {{ $hebergement->ville }}</p>
                </div>

                <form action="{{ route('proprietaire.hebergements.chambres.store', $hebergement->id) }}" method="POST" enctype="multipart/form-data" x-data="{
                    chambreType: '',
                    showImagePreview: false,
                    imagePreviews: [],
                    selectedEquipements: [],
                    
                    previewImages() {
                        this.imagePreviews = [];
                        this.showImagePreview = false;
                        
                        const files = document.getElementById('images').files;
                        if (files.length > 0) {
                            this.showImagePreview = true;
                            for (let i = 0; i < files.length; i++) {
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    this.imagePreviews.push(e.target.result);
                                };
                                reader.readAsDataURL(files[i]);
                            }
                        }
                    },
                    
                    removeImage(index) {
                        const dt = new DataTransfer();
                        const files = document.getElementById('images').files;
                        
                        for (let i = 0; i < files.length; i++) {
                            if (i !== index) {
                                dt.items.add(files[i]);
                            }
                        }
                        
                        document.getElementById('images').files = dt.files;
                        this.previewImages();
                    }
                }">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label for="numero" class="block text-sm font-medium text-gray-700">Numéro de chambre</label>
                                <input type="text" name="numero" id="numero" value="{{ old('numero') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Ex: 101">
                                @error('numero')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="nom" class="block text-sm font-medium text-gray-700">Nom de la chambre <span class="text-red-500">*</span></label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required placeholder="Ex: Chambre Deluxe">
                                @error('nom')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="type_chambre" class="block text-sm font-medium text-gray-700">Type de chambre <span class="text-red-500">*</span></label>
                                <select name="type_chambre" id="type_chambre" x-model="chambreType" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="standard" {{ old('type_chambre') == 'standard' ? 'selected' : '' }}>Standard</option>
                                    <option value="deluxe" {{ old('type_chambre') == 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                                    <option value="suite" {{ old('type_chambre') == 'suite' ? 'selected' : '' }}>Suite</option>
                                    <option value="familiale" {{ old('type_chambre') == 'familiale' ? 'selected' : '' }}>Familiale</option>
                                    <option value="autre" {{ old('type_chambre') == 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('type_chambre')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="capacite" class="block text-sm font-medium text-gray-700">Capacité (personnes) <span class="text-red-500">*</span></label>
                                <input type="number" name="capacite" id="capacite" value="{{ old('capacite', 1) }}" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                @error('capacite')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="prix" class="block text-sm font-medium text-gray-700">Prix par nuit (€) <span class="text-red-500">*</span></label>
                                <input type="number" name="prix" id="prix" value="{{ old('prix') }}" min="0" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                @error('prix')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="est_disponible" id="est_disponible" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('est_disponible') ? 'checked' : 'checked' }}>
                                    <label for="est_disponible" class="ml-2 block text-sm text-gray-700">Disponible à la réservation</label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="est_visible" id="est_visible" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('est_visible') ? 'checked' : 'checked' }}>
                                    <label for="est_visible" class="ml-2 block text-sm text-gray-700">Visible sur le site</label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
                                <textarea name="description" id="description" rows="5" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Équipements</label>
                                <div class="grid grid-cols-2 gap-2 max-h-60 overflow-y-auto p-2 border border-gray-300 rounded-md">
                                    @foreach($equipements as $equipement)
                                    <div class="flex items-center">
                                        <input id="equipement-{{ $equipement->id }}" name="equipements[]" value="{{ $equipement->id }}" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ in_array($equipement->id, old('equipements', [])) ? 'checked' : '' }}>
                                        <label for="equipement-{{ $equipement->id }}" class="ml-2 text-sm text-gray-700">{{ $equipement->nom }}</label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('equipements')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="images" class="block text-sm font-medium text-gray-700">Images</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Télécharger des images</span>
                                                <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" @change="previewImages()">
                                            </label>
                                            <p class="pl-1">ou glisser-déposer</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2MB</p>
                                    </div>
                                </div>
                                @error('images.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="showImagePreview" class="mt-2">
                                <p class="text-sm font-medium text-gray-700 mb-2">Aperçu des images :</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                    <template x-for="(preview, index) in imagePreviews" :key="index">
                                        <div class="relative group">
                                            <img :src="preview" class="h-24 w-full object-cover rounded border border-gray-300">
                                            <button type="button" @click="removeImage(index)" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 transform translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Enregistrer la chambre
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Validation personnalisée si nécessaire
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            // Validation côté client ici si nécessaire
        });
    });
</script>
@endpush