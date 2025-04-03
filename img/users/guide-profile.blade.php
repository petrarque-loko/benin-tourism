@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 pt-16 pb-24"



<div class="bg-cover bg-center bg-fixed min-h-screen py-6" 
     style="background-image: url('/images/background.jpg');">
    <div " 
         x-data="siteEditor()">
        <!-- Reste du contenu... -->
    </div>


    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Carte principale -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 transform transition-all duration-300 hover:shadow-2xl">
            <!-- Header avec photo de profil et info principale -->
            <div class="relative">
                <!-- Bannière décorative -->
                <div class="h-40 bg-gradient-to-r from-lime-400 to-yellow-500"></div>
                
                <!-- Infos principales du guide -->
                <div class="px-6 sm:px-8 pb-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-end -mt-16 sm:-mt-20 relative z-10">
                        <!-- Photo de profil -->
                        <div class="relative mb-4 sm:mb-0 sm:mr-6">
                            <img src="{{ $user->image ?? '/images/1.png'}}" alt="{{ $user->nom }}" 
                                class="w-24 h-24 sm:w-32 sm:h-32 rounded-full border-4 border-white shadow-lg object-cover">
                            <div class="absolute bottom-0 right-0 bg-green-500 rounded-full w-6 h-6 border-4 border-white flex items-center justify-center">
                                <span class="sr-only">En ligne</span>
                            </div>
                        </div>
                        
                        <!-- Info principale -->
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $user->nom }} {{ $user->prenom }}</h1>
                            <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm">
                                <div class="flex items-center text-indigo-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M10 4a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 4z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M10 10a.75.75 0 01.75.75v.01a.75.75 0 01-1.5 0V10.75A.75.75 0 0110 10z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Guide Touristique</span>
                                </div>
                                <div class="flex items-center gap-1 text-amber-500 font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="font-semibold">{{ $noteMoyenne ?? 'N/A' }}</span>
                                    <span class="text-gray-500">({{ $totalCommentaires ?? 0 }} avis)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bouton de contact -->
                        <div class="mt-4 sm:mt-0">
                            <button class="bg-indigo-600 hover:bg-indigo-700 transform hover:scale-105 transition-all duration-300 text-white px-6 py-2 rounded-full font-medium flex items-center shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                Contacter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contenu principal sur deux colonnes -->
            <div class="px-6 sm:px-8 py-6">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Colonne 1: Informations -->
                    <div class="space-y-6">
                        <!-- Informations personnelles -->
                        <div class="bg-gray-50 rounded-xl p-6 shadow-sm transform transition-all duration-300 hover:shadow-md">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                Informations personnelles
                            </h2>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                    <div>
                                        <div class="text-sm text-gray-500">Email</div>
                                        <div class="font-medium">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                    <div>
                                        <div class="text-sm text-gray-500">Téléphone</div>
                                        <div class="font-medium">{{ $user->telephone ?? 'Non renseigné' }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.724 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L13 11.236 11.618 14z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <div class="text-sm text-gray-500">Langues parlées</div>
                                        <div class="font-medium">{{ $user->langues ?? 'Non renseigné' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Spécialités ou section supplémentaire -->
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-6 shadow-sm transform transition-all duration-300 hover:shadow-md">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Expérience
                            </h2>
                            <div class="prose text-gray-600">
                                <p>Guide touristique professionnel avec plusieurs années d'expérience dans l'accompagnement de groupes et l'organisation de circuits personnalisés.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne 2: Circuits proposés -->
                    <div class="bg-white rounded-xl p-6 shadow-sm transform transition-all duration-300 hover:shadow-md">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            Circuits proposés
                        </h2>
                        
                      @if($circuits->count() > 0)
                <div class="space-y-3">
                    @foreach($circuits as $circuit)
                        <a href="{{ route('circuits.show', $circuit->id) }}" 
                        class="group block bg-gray-50 rounded-lg p-4 transition-all duration-300 hover:bg-indigo-50 hover:shadow-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-14 w-14 bg-indigo-100 rounded-lg overflow-hidden">
                                    @if($circuit->image_url)
                                        <img src="{{ $circuit->image_url }}" alt="{{ $circuit->titre }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-indigo-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex justify-between">
                                        <h3 class="text-lg font-medium text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">{{ $circuit->titre }}</h3>
                                        <div class="text-sm font-medium text-indigo-600">{{ $circuit->duree }} {{ $circuit->duree > 1 ? 'jours' : 'jour' }}</div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $circuit->description }}</p>
                                    <div class="mt-2 flex items-center text-sm">
                                        <div class="flex items-center text-amber-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="ml-1">{{ $circuit->note_moyenne ?? 'N/A' }}</span>
                                        </div>
                                        <span class="mx-2 text-gray-400">|</span>
                                        <div class="flex items-center text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="ml-1">{{ $circuit->total_commentaires ?? 0 }} avis</span>
                                        </div>
                                        <span class="mx-2 text-gray-400">|</span>
                                        <div class="flex items-center text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v5a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="ml-1">{{ $circuit->nombre_visites ?? 0 }} visites</span>
                                        </div>
                                        <span class="mx-2 text-gray-400">|</span>
                                        <div class="flex items-center text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="ml-1">{{ $circuit->prix }}€</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun circuit proposé</h3>
                    <p class="mt-1 text-gray-500">Ce guide touristique n'a pas encore créé de circuits.</p>
                </div>
            @endif
            </div>
            </div>
            </div>

            <!-- Section des commentaires -->
            <div class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 sm:p-8">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                        </svg>
                        Commentaires ({{ $totalCommentaires ?? 0 }})
                    </h2>
                    
                    @if(isset($commentaires) && $commentaires->count() > 0)
                        <div class="space-y-6">
                            @foreach($commentaires as $commentaire)
                                <div class="bg-gray-50 rounded-xl p-5">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <img class="h-10 w-10 rounded-full" src="{{ $commentaire->utilisateur->photo_url ?? '/default-avatar.png' }}" alt="{{ $commentaire->utilisateur->nom ?? 'Utilisateur' }}">
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-sm font-medium text-gray-900">{{ $commentaire->utilisateur->nom ?? 'Utilisateur' }}</h3>
                                                <div class="flex items-center">
                                                    <div class="flex items-center text-amber-500">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="{{ $i <= $commentaire->note ? 'currentColor' : 'none' }}">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                    <time class="ml-2 text-sm text-gray-500">{{ $commentaire->created_at->format('d/m/Y') }}</time>
                                                </div>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-600">
                                                <p>{{ $commentaire->contenu }}</p>
                                            </div>
                                            <div class="mt-3 flex items-center space-x-4">
                                                <button type="button" class="flex items-center text-sm text-gray-500 hover:text-indigo-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                                                    </svg>
                                                    Utile
                                                </button>
                                                <button type="button" class="flex items-center text-sm text-gray-500 hover:text-indigo-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z" />
                                                    </svg>
                                                    Signaler
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun commentaire pour l'instant</h3>
                <p class="mt-1 text-gray-500">Soyez le premier à donner votre avis sur ce guide.</p>
            </div>
        @endif

        @auth
            <div class="mt-8 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900">Laissez votre commentaire</h3>
                    @csrf
                    
                    <div class="mb-4">
                        <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Votre note</label>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-rating text-2xl text-gray-300 hover:text-amber-500 focus:text-amber-500" data-value="{{ $i }}">★</button>
                            @endfor
                            <input type="hidden" name="note" id="note" value="0">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="contenu" class="block text-sm font-medium text-gray-700 mb-1">Votre commentaire</label>
                        <textarea id="contenu" name="contenu" rows="4" class="shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" placeholder="Partagez votre expérience avec ce guide..."></textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Publier
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="mt-8 border-t border-gray-200 pt-6 text-center">
                <p class="text-gray-600">Vous devez être <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500">connecté</a> pour laisser un commentaire.</p>
            </div>
        @endauth
    </div>
</div>


<!-- Script pour les étoiles de notation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating');
    const noteInput = document.getElementById('note');
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = parseInt(this.dataset.value);
            noteInput.value = value;
            
            // Réinitialiser toutes les étoiles
            stars.forEach(s => s.classList.remove('text-amber-500'));
            stars.forEach(s => s.classList.add('text-gray-300'));
            
            // Colorier les étoiles jusqu'à celle cliquée
            for(let i = 0; i < stars.length; i++) {
                if(i < value) {
                    stars[i].classList.remove('text-gray-300');
                    stars[i].classList.add('text-amber-500');
                }
            }
        });
    });
});
</script>
@endsection












