@extends('layouts.admin')

@section('title', 'Détails du Site Touristique')

@section('content')
<div class="container mx-auto px-4 py-6  min-h-screen " x-data="{ activeTab: 'informations' }"
style="background-image: url('/images/background.jpg');" 
x-data="siteEditor()">


   



    <!-- En-tête -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">{{ $site->nom }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.sites.edit', $site) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Modifier
            </a>
            <a href="{{ route('admin.sites.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Navigation par onglets -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex flex-wrap -mb-px">
            <button @click="activeTab = 'informations'" :class="{'border-b-2 border-blue-500 text-blue-600': activeTab === 'informations'}" class="px-4 py-2 font-medium text-sm hover:text-blue-700 transition-colors duration-200">
                Informations générales
            </button>
            <button @click="activeTab = 'galerie'" :class="{'border-b-2 border-blue-500 text-blue-600': activeTab === 'galerie'}" class="px-4 py-2 font-medium text-sm hover:text-blue-700 transition-colors duration-200">
                Galerie d'images ({{ $site->medias->count() }})
            </button>
            <button @click="activeTab = 'commentaires'" :class="{'border-b-2 border-blue-500 text-blue-600': activeTab === 'commentaires'}" class="px-4 py-2 font-medium text-sm hover:text-blue-700 transition-colors duration-200">
                Avis ({{ $site->commentaires->count() }})
            </button>
            <button @click="activeTab = 'reservations'" :class="{'border-b-2 border-blue-500 text-blue-600': activeTab === 'reservations'}" class="px-4 py-2 font-medium text-sm hover:text-blue-700 transition-colors duration-200">
                Réservations ({{ $site->reservations->count() }})
            </button>
        </nav>
    </div>

    <!-- Contenu des onglets -->
    <div>
        <!-- Informations générales -->
        <div x-show="activeTab === 'informations'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Carte d'information -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-500 to-blue-600">
                        <h3 class="text-lg font-medium text-white">Informations du site</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="flex items-center text-gray-700 mb-3">
                                    <span class="font-semibold mr-2">ID:</span> 
                                    <span class="bg-gray-100 px-2 py-1 rounded">{{ $site->id }}</span>
                                </p>
                                <p class="flex items-center text-gray-700 mb-3">
                                    <span class="font-semibold mr-2">Nom:</span> 
                                    <span>{{ $site->nom }}</span>
                                </p>
                                <p class="flex items-center text-gray-700 mb-3">
                                    <span class="font-semibold mr-2">Catégorie:</span> 
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ $site->categorie->nom ?? 'Non catégorisé' }}</span>
                                </p>
                                <p class="flex items-center text-gray-700 mb-3">
                                    <span class="font-semibold mr-2">Localisation:</span> 
                                    <span>{{ $site->localisation }}</span>
                                </p>
                            </div>
                            <div>
                                <p class="flex items-center text-gray-700 mb-3">
                                    <span class="font-semibold mr-2">Latitude:</span> 
                                    <span>{{ $site->latitude }}</span>
                                </p>
                                <p class="flex items-center text-gray-700 mb-3">
                                    <span class="font-semibold mr-2">Longitude:</span> 
                                    <span>{{ $site->longitude }}</span>
                                </p>
                                <p class="flex items-center text-gray-700 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-semibold mr-2">Créé le:</span> 
                                    <span>{{ $site->created_at->format('d/m/Y à H:i') }}</span>
                                </p>
                                <p class="flex items-center text-gray-700 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-semibold mr-2">Modifié le:</span> 
                                    <span>{{ $site->updated_at->format('d/m/Y à H:i') }}</span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <h5 class="font-semibold text-gray-800 mb-3">Description</h5>
                            <div class="prose max-w-none">
                                {!! $site->description !!}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte pour la carte (map) -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-green-500 to-green-600">
                        <h3 class="text-lg font-medium text-white">Localisation</h3>
                    </div>
                    <div class="p-4">
                        <div id="map" class="h-64 md:h-80 rounded-lg shadow-inner" x-init="initMap()"></div>
                        <div class="mt-3 flex justify-center">
                            <button class="inline-flex items-center px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition-colors duration-200" onclick="centerMap()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Centrer la carte
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Galerie d'images -->
        <div x-show="activeTab === 'galerie'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-purple-500 to-purple-600">
                    <h3 class="text-lg font-medium text-white">Galerie d'images ({{ $site->medias->count() }})</h3>
                </div>
                <div class="p-6">
                    @if($site->medias->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-data="{ lightboxOpen: false, currentImage: '' }">
                            @foreach($site->medias as $media)
                            <div class="overflow-hidden rounded-lg shadow-md group transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                                <a href="#" @click.prevent="lightboxOpen = true; currentImage = '{{ asset('storage/' . $media->url) }}'">
                                    <img src="{{ asset('storage/' . $media->url) }}" alt="Image du site" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                                </a>
                            </div>
                            @endforeach
                            
                            <!-- Lightbox Modal -->
                            <div x-show="lightboxOpen" @keydown.escape.window="lightboxOpen = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" @click="lightboxOpen = false">
                                <div @click.stop class="relative max-w-4xl max-h-screen p-2">
                                    <button @click="lightboxOpen = false" class="absolute top-3 right-3 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <img :src="currentImage" class="max-h-screen max-w-full rounded-lg" alt="Image agrandie">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-gray-600">Aucune image disponible pour ce site.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Commentaires et avis -->
        <div x-show="activeTab === 'commentaires'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-data="{ searchTerm: '' }">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-yellow-500 to-yellow-600">
                    <h3 class="text-lg font-medium text-white">Commentaires et Avis ({{ $site->commentaires->count() }})</h3>
                </div>
                <div class="p-6">
                    @if($site->commentaires->count() > 0)
                        <div class="mb-4 flex justify-between items-center">
                            <div class="relative w-64">
                                <input type="text" x-model="searchTerm" placeholder="Rechercher un commentaire..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-300 focus:border-blue-300 transition-colors duration-200">
                                <div class="absolute left-3 top-2.5 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 rounded-lg border border-gray-300 text-sm hover:bg-gray-100 transition-colors duration-200">Tout</button>
                                <button class="px-3 py-1 rounded-lg border border-green-300 text-sm text-green-700 bg-green-50 hover:bg-green-100 transition-colors duration-200">Visibles</button>
                                <button class="px-3 py-1 rounded-lg border border-red-300 text-sm text-red-700 bg-red-50 hover:bg-red-100 transition-colors duration-200">Masqués</button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commentaire</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($site->commentaires as $commentaire)
                                    <tr x-show="!searchTerm || '{{ strtolower($commentaire->contenu) }}'.includes(searchTerm.toLowerCase()) || '{{ strtolower($commentaire->user->name) }}'.includes(searchTerm.toLowerCase())" class="{{ $commentaire->is_hidden ? 'bg-gray-100' : '' }} hover:bg-gray-50 transition-colors duration-150" x-transition>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 font-bold">
                                                        {{ substr($commentaire->user->nom, 0, 1) }}{{ substr($commentaire->user->prenom, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $commentaire->user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($commentaire->note)
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $commentaire->note)
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                    <span class="ml-1 text-sm text-gray-600">({{ $commentaire->note }})</span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500">Pas de note</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-md truncate" x-data="{ expanded: false }">
                                                <p x-show="!expanded" @click="expanded = true" class="cursor-pointer hover:text-blue-600">{{ Str::limit($commentaire->contenu, 50) }}</p>
                                                <div x-show="expanded" @click.outside="expanded = false" class="bg-white p-4 shadow-lg rounded-lg absolute z-10">
                                                    <p>{{ $commentaire->contenu }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $commentaire->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('admin.commentaires.toggle-visibility', $commentaire) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium {{ $commentaire->is_hidden ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' }} transition-colors duration-200">
                                                    @if($commentaire->is_hidden)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Afficher
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                        </svg>
                                                        Masquer
                                                    @endif
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <p class="mt-2 text-gray-600">Aucun commentaire pour ce site.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Réservations -->
        <div x-show="activeTab === 'reservations'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" x-data="{ statusFilter: 'all' }">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-indigo-500 to-indigo-600">
                    <h3 class="text-lg font-medium text-white">Réservations ({{ $site->reservations->count() }})</h3>
                </div>
                <div class="p-6">
                    @if($site->reservations->count() > 0)
                        <div class="mb-4 flex justify-between items-center">
                            <div class="flex space-x-2">
                                <button @click="statusFilter = 'all'" :class="{'bg-indigo-100 text-indigo-700 border-indigo-300': statusFilter === 'all'}" class="px-3 py-1 rounded-lg border border-gray-300 text-sm hover:bg-gray-100 transition-colors duration-200">Toutes</button>
                                <button @click="statusFilter = 'confirmé'" :class="{'bg-green-100 text-green-700 border-green-300': statusFilter === 'confirmé'}" class="px-3 py-1 rounded-lg border border-gray-300 text-sm hover:bg-gray-100 transition-colors duration-200">Confirmées</button>
                                <button @click="statusFilter = 'en_attente'" :class="{'bg-yellow-100 text-yellow-700 border-yellow-300': statusFilter === 'en_attente'}" class="px-3 py-1 rounded-lg border border-gray-300 text-sm hover:bg-gray-100 transition-colors duration-200">En attente</button>
                                <button @click="statusFilter = 'annulé'" :class="{'bg-red-100 text-red-700 border-red-300': statusFilter === 'annulé'}" class="px-3 py-1 rounded-lg border border-gray-300 text-sm hover:bg-gray-100 transition-colors duration-200">Annulées</button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date début</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date fin</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($site->reservations as $reservation)
                                    <tr x-show="statusFilter === 'all' || statusFilter === '{{ $reservation->statut }}'" x-transition class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $reservation->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                                        <a href="{{ route('users.show', $reservation->user->id) }}">
                                                        {{ substr($reservation->user->nom, 0, 1) }}
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $reservation->user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $reservation->date_debut->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $reservation->date_fin->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($reservation->statut == 'confirmé')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Confirmé
                                                </span>
                                            @elseif($reservation->statut == 'en_attente')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    En attente
                                                </span>
                                            @elseif($reservation->statut == 'annulé')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Annulé
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.sites.reservations.show', $reservation) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Détails
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-gray-600">Aucune réservation pour ce site.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>

<script>
    // Variable pour la carte
    let map;
    let marker;

    function initMap() {
        // Récupérer les coordonnées du site
        const lat = {{ $site->latitude }};
        const lng = {{ $site->longitude }};
        const siteName = "{{ $site->nom }}";
        
        // Créer un objet LatLng pour la position
        const position = {lat: lat, lng: lng};
        
        // Initialiser la carte
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: position,
            styles: [
                {
                    "featureType": "all",
                    "elementType": "geometry",
                    "stylers": [{"color": "#f5f5f5"}]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [{"color": "#e9e9e9"}]
                }
            ]
        });
        
        // Ajouter un marqueur à la position du site
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: siteName,
            animation: google.maps.Animation.DROP
        });
        
        // Ajouter une info-bulle au marqueur
        const infowindow = new google.maps.InfoWindow({
            content: `<strong>${siteName}</strong><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`
        });
        
        // Afficher l'info-bulle au clic sur le marqueur
        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });
    }

    function centerMap() {
        // Recentrer la carte sur le marqueur
        if (map && marker) {
            map.setCenter(marker.getPosition());
            map.setZoom(13);
        }
    }
</script>
@endpush
reservations