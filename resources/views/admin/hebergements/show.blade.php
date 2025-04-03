<!-- resources/views/admin/hebergements/show.blade.php -->
@extends('layouts.admin')
@section('title', 'Détails de l\'hébergement')
@section('content')
<div class="py-6" x-data="{ 
    activeTab: 'infos',
    showImageModal: false,
    currentImage: '',
    openImage(url) {
        this.currentImage = url;
        this.showImageModal = true;
    }
}">
    <!-- En-tête avec boutons d'action -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ $hebergement->nom }}
                </h2>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $hebergement->ville ?? 'Non spécifié' }}, {{ $hebergement->pays ?? 'Non spécifié' }}
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"></path>
                        </svg>
                        Propriétaire: {{ $hebergement->proprietaire ? $hebergement->proprietaire->nom . ' ' . $hebergement->proprietaire->prenom : 'Non assigné' }}
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm8 8v2h1v1H4v-1h1v-2a1 1 0 011-1h8a1 1 0 011 1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $hebergement->typeHebergement->nom }}
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-2">
                @can('update', $hebergement)
                <a href="{{ route('admin.hebergements.edit', $hebergement) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
                @endcan
                
                @can('update', $hebergement)
                <form action="{{ route('admin.hebergements.toggle-visibility', $hebergement) }}" method="POST">
                    @csrf
                    <button type="submit" class="{{ $hebergement->disponibilite ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        {{ $hebergement->disponibilite ? 'Rendre indisponible' : 'Rendre disponible' }}
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>

    <!-- Onglets de navigation -->
    <div class="mt-6 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'infos'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'infos', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'infos'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Informations
                </button>
                <button @click="activeTab = 'chambres'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'chambres', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'chambres'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Chambres ({{ $hebergement->chambres->count() }})
                </button>
                <button @click="activeTab = 'photos'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'photos', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'photos'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Photos ({{ $hebergement->medias->count() }})
                </button>
                <button @click="activeTab = 'commentaires'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'commentaires', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'commentaires'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Commentaires ({{ $hebergement->commentaires->count() }})
                </button>
            </nav>
        </div>
    </div>

    <!-- Contenu des onglets -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Informations générales -->
        <div x-show="activeTab === 'infos'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Détails de l'hébergement</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Informations complètes sur l'hébergement</p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Nom</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hebergement->nom }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hebergement->description ?? 'Aucune description disponible' }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Type</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hebergement->typeHebergement->nom }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $hebergement->adresse ?? 'Non spécifiée' }}<br>
                                {{ $hebergement->ville ?? 'Non spécifiée' }}<br>
                                {{ $hebergement->pays ?? 'Non spécifié' }}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Prix</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hebergement->prix_min }} - {{ $hebergement->prix_max }} FCFA</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Statut</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $hebergement->disponibilite ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $hebergement->disponibilite ? 'Disponible' : 'Indisponible' }}
                                </span>
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Propriétaire</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $hebergement->proprietaire ? $hebergement->proprietaire->nom . ' ' . $hebergement->proprietaire->prenom : 'Non assigné' }} 
                                ({{ $hebergement->proprietaire->email ?? 'N/A' }})
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hebergement->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Dernière mise à jour</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hebergement->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Chambres -->
        <div x-show="activeTab === 'chambres'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="border-t border-gray-200">
                    @if($hebergement->chambres->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($hebergement->chambres as $chambre)
                        <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-10 w-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-semibold">{{ $chambre->nom }}</h4>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                {{ $chambre->capacite }} personnes
                                            </span>
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                {{ $chambre->prix }} € / nuit
                                            </span>
                                            <span class="px-2 py-1 text-xs rounded-full {{ $chambre->disponibilite ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $chambre->disponibilite ? 'Disponible' : 'Indisponible' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.chambres.show', $chambre) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Voir
                                    </a>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">{{ Str::limit($chambre->description ?? 'Aucune description', 150) }}</p>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune chambre disponible</h3>
                        <p class="mt-1 text-sm text-gray-500">Commencez par ajouter une chambre à cet hébergement.</p>
                        @can('update', $hebergement)
                        <div class="mt-6">
                            <a href="{{ route('admin.chambres.create') }}?hebergement_id={{ $hebergement->id }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Ajouter votre première chambre
                            </a>
                        </div>
                        @endcan
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Photos -->
        <div x-show="activeTab === 'photos'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Photos de l'hébergement</h3>
                </div>
                <div class="border-t border-gray-200">
                    @if($hebergement->medias->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4">
                        @foreach($hebergement->medias as $media)
                        <div class="relative group">
                            <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-200">
                                <img src="{{ asset('storage/' . $media->url ) }}" alt="{{ $hebergement->nom }}" class="object-cover cursor-pointer" @click="openImage('{{ asset('storage/' . $media->chemin) }}')">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune photo disponible</h3>
                        <p class="mt-1 text-sm text-gray-500">Commencez par ajouter des photos à cet hébergement.</p>
                        @can('update', $hebergement)
                        <div class="mt-6">
                            <a href="{{ route('admin.hebergements.edit', $hebergement) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Ajouter des photos
                            </a>
                        </div>
                        @endcan
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Commentaires -->
        <div x-show="activeTab === 'commentaires'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Commentaires</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Liste des commentaires sur cet hébergement</p>
                </div>
                <div class="border-t border-gray-200">
                    @if($hebergement->commentaires->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($hebergement->commentaires as $commentaire)
                        <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5v-2a2 2 0 012-2h10a2 2 0 012 2v2h-4m-6 0a4 4 0 01-4-4V6a4 4 0 118 0v6a4 4 0 01-4 4z" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                {{ $commentaire->user->nom . ' ' . $commentaire->user->prenom }}
                                            </h4>
                                            <p class="mt-1 text-sm text-gray-600">{{ $commentaire->contenu }}</p>
                                            <p class="mt-1 text-xs text-gray-400">{{ $commentaire->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @can('hide', $commentaire)
                                <form action="{{ route('admin.hebergements.comments.toggle-visibility', [$hebergement->id, $commentaire->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md {{ $commentaire->is_hidden ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ $commentaire->is_hidden ? 'Rendre visible' : 'Masquer' }}
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5v-2a2 2 0 012-2h10a2 2 0 012 2v2h-4m-6 0a4 4 0 01-4-4V6a4 4 0 118 0v6a4 4 0 01-4 4z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun commentaire</h3>
                        <p class="mt-1 text-sm text-gray-500">Cet hébergement n'a pas encore reçu de commentaires.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les images -->
    <div 
        x-show="showImageModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="fixed inset-0 bg-black bg-opacity-75" @click="showImageModal = false"></div>
        <div class="relative z-10 max-w-4xl mx-auto">
            <img :src="currentImage" alt="Image de l'hébergement" class="w-full rounded-lg">
            <button @click="showImageModal = false" class="absolute top-4 right-4 bg-white rounded-full p-2 hover:bg-gray-100 focus:outline-none">
                <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        window.axios = window.axios || {};
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    });
</script>
@endsection