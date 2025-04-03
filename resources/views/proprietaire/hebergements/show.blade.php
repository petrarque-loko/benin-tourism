@extends('layouts.proprietaire')

@section('content')
<div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $hebergement->nom }}</h1>
            <p class="mt-1 text-sm text-gray-600">
                {{ $hebergement->ville }} · {{ $hebergement->typeHebergement->nom }}
            </p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('proprietaire.hebergements.edit', $hebergement) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Modifier
            </a>
            <form action="{{ route('proprietaire.hebergements.destroy', $hebergement) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet hébergement ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Supprimer
                </button>
            </form>
            <a href="{{ route('proprietaire.hebergements.reservations.index', $hebergement) }}">Réservations pour cet hébergement</a>
        </div>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
            <button @click="show = false" class="float-right">&times;</button>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Photos de l'hébergement -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Photos</h3>
        </div>
        <div class="px-4 py-5 sm:p-6 bg-gray-50">
            <div x-data="{ activeSlide: 0, totalSlides: {{ count($hebergement->medias) }} }" class="relative">
                @if(count($hebergement->medias) > 0)
                    <div class="flex overflow-hidden rounded-lg h-96 mb-2">
                        @foreach($hebergement->medias as $index => $media)
                            <div x-show="activeSlide === {{ $index }}" class="w-full h-full flex-shrink-0">
                                <img src="{{ Storage::url($media->url) }}" alt="{{ $hebergement->nom }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                    
                    @if(count($hebergement->medias) > 1)
                        <div class="absolute inset-y-0 left-0 flex items-center">
                            <button @click="activeSlide = (activeSlide - 1 + totalSlides) % totalSlides" class="bg-gray-900 bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 text-white focus:outline-none">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center">
                            <button @click="activeSlide = (activeSlide + 1) % totalSlides" class="bg-gray-900 bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 text-white focus:outline-none">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Indicateurs en bas -->
                        <div class="flex justify-center mt-2">
                            @foreach($hebergement->medias as $index => $media)
                                <button @click="activeSlide = {{ $index }}" class="mx-1 w-3 h-3 rounded-full" :class="activeSlide === {{ $index }} ? 'bg-blue-600' : 'bg-gray-300'"></button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="flex items-center justify-center h-64 bg-gray-100 rounded-lg">
                        <p class="text-gray-500">Aucune photo disponible</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Informations de l'hébergement -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Informations générales</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $hebergement->disponibilite ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $hebergement->disponibilite ? 'Disponible' : 'Non disponible' }}
                        </span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Type d'hébergement</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hebergement->typeHebergement->nom }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hebergement->adresse }}, {{ $hebergement->ville }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Prix</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hebergement->prix_min }} € - {{ $hebergement->prix_max }} €</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Coordonnées GPS</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($hebergement->latitude && $hebergement->longitude)
                            {{ $hebergement->latitude }}, {{ $hebergement->longitude }}
                        @else
                            Non spécifiées
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hebergement->description }}</dd>
                </div>
            </dl>
        </div>

        <!-- Chambres -->
        <div class="px-4 py-5 sm:px-6 border-t border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Chambres</h3>
                <a href="{{ route('proprietaire.hebergements.chambres.index', ['hebergement' => $hebergement->id]) }}" 
                class="text-sm font-medium text-blue-600 hover:text-blue-500">
                    Gérer les chambres
                </a>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-5 sm:p-6">
            @if(count($hebergement->chambres) > 0)
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($hebergement->chambres as $chambre)
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h4 class="text-lg font-medium text-gray-900">{{ $chambre->nom }}</h4>
                                <p class="mt-1 text-sm text-gray-500">{{ $chambre->description }}</p>
                                <div class="mt-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $chambre->capacite }} {{ $chambre->capacite > 1 ? 'personnes' : 'personne' }}
                                    </span>
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $chambre->prix }} € / nuit
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-500">Aucune chambre n'a été ajoutée pour cet hébergement.</p>
                    <a href="{{ route('proprietaire.hebergements.chambres.create', ['hebergement' => $hebergement->id]) }}" class="mt-2 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Ajouter une chambre
                    </a>
                </div>
            @endif
        </div>

        <!-- Commentaires -->
        <div class="px-4 py-5 sm:px-6 border-t border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Avis des voyageurs</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            @if(count($hebergement->commentaires) > 0)
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($hebergement->commentaires as $commentaire)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-500">{{ substr($commentaire->user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-900">{{ $commentaire->user->name }}</span>
                                                </div>
                                                <p class="mt-0.5 text-sm text-gray-500">
                                                    {{ $commentaire->created_at->format('d/m/Y') }}
                                                </p>
                                                <div class="mt-1 flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="h-5 w-5 {{ $i <= $commentaire->note ? 'text-yellow-400' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p>{{ $commentaire->contenu }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucun avis pour le moment.</p>
            @endif
        </div>
    </div>

    <div class="mt-6 flex justify-between">
        <a href="{{ route('proprietaire.hebergements.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Retour à la liste
        </a>
    </div>
</div>
@endsection