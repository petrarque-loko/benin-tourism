@php
    $layout = 'layouts.app';
    if (auth()->user()->isAdmin()) {
        $layout = 'layouts.admin';
    } elseif (auth()->user()->isGuide()) {
        $layout = 'layouts.guide';
    } elseif (auth()->user()->isProprietaire()) {
        $layout = 'layouts.proprietaire';
    }
@endphp

@extends($layout)
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 pt-16 pb-24"



<div class="bg-cover bg-center bg-fixed min-h-screen py-6" 
     style="background-image: url('/images/background.jpg');">
    <div 
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
                            <img src=" /images/1.png" alt="{{ $user->nom }}" 
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
                                    <span class="text-gray-500">{{ $totalCommentaires ?? 0 }} avis</span>
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
                                        <h3 class="text-lg font-medium text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">{{ $circuit->nom }}</h3>
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

        <!-- Section commentaires -->
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Avis et commentaires</h2>
                
                <!-- Métadonnées pour le composant de commentaires -->
                <meta name="commentable-type" content="guide">
                <meta name="commentable-id" content="{{ $user->id }}">
                @auth
                <meta name="user-id" content="{{ auth()->id() }}">
                <meta name="user-role" content="{{ auth()->user()->role }}">
                @endauth
                
                <!-- Insertion du composant de commentaires -->
                <div x-data="commentairesComponent()" x-init="init()" class="commentaires-section">
                    <!-- Formulaire d'ajout de commentaire -->
                    <div x-show="canComment" class="bg-gray-50 p-4 rounded shadow mb-4">
                        <h3 class="text-lg font-bold mb-2">Ajouter un commentaire</h3>
                        <div class="mb-3">
                            <label class="block mb-1">Note</label>
                            <div class="flex space-x-1">
                                <template x-for="i in 5">
                                    <button @click="newComment.note = i" 
                                            class="text-2xl focus:outline-none"
                                            :class="newComment.note >= i ? 'text-yellow-400' : 'text-gray-300'">★</button>
                                </template>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1">Votre commentaire</label>
                            <textarea x-model="newComment.contenu" 
                                    class="w-full p-2 border rounded" 
                                    rows="3" 
                                    placeholder="Partagez votre expérience avec ce guide..."></textarea>
                        </div>
                        <div class="text-red-500 mb-2" x-text="errorMessage"></div>
                        <button @click="submitComment()" 
                                class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
                                :disabled="submitting">
                            <span x-show="submitting">Envoi en cours...</span>
                            <span x-show="!submitting">Publier</span>
                        </button>
                    </div>

                    <!-- Message si l'utilisateur ne peut pas commenter -->
                    <div x-show="!canComment && isLoggedIn && !userHasCommented" class="bg-yellow-100 p-4 rounded mb-4">
                        <p>Vous devez avoir participé à un circuit avec ce guide pour pouvoir le commenter.</p>
                    </div>

                    <!-- Liste des commentaires -->
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <div class="text-2xl font-bold mr-2" x-text="noteMoyenne"></div>
                            <div class="flex">
                                <template x-for="i in 5">
                                    <div class="text-xl text-yellow-400" 
                                        :class="noteMoyenne >= i ? 'text-yellow-400' : 'text-gray-300'">★</div>
                                </template>
                            </div>
                            <div class="ml-2 text-gray-600" x-text="`(${totalCommentaires} avis)`"></div>
                        </div>
                    </div>

                    <!-- Liste des commentaires -->
                    <div class="space-y-4">
                        <template x-for="(comment, index) in commentaires" :key="comment.id">
                            <div class="bg-gray-50 p-4 rounded shadow">
                                <div class="flex justify-between">
                                    <div class="font-bold" x-text="`${comment.user.prenom} ${comment.user.nom}`"></div>
                                    <div class="text-gray-500" x-text="formatDate(comment.created_at)"></div>
                                </div>
                                <div class="flex my-1">
                                    <template x-for="i in 5">
                                        <div class="text-xl" :class="comment.note >= i ? 'text-yellow-400' : 'text-gray-300'">★</div>
                                    </template>
                                </div>
                                
                                <!-- Mode lecture -->
                                <div x-show="!comment.editing" class="mt-2">
                                    <p x-text="comment.contenu"></p>
                                    
                                    <!-- Actions (modifier/supprimer) pour l'auteur du commentaire -->
                                    <div x-show="userId === comment.user_id" class="mt-2 flex space-x-2">
                                        <button @click="editComment(index)" 
                                                class="text-blue-500 hover:text-blue-700 p-1 rounded hover:bg-gray-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button @click="deleteComment(comment.id)" 
                                                class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-gray-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Action pour l'administrateur -->
                                    <div x-show="isAdmin && userId !== comment.user_id" class="mt-2">
                                        <button @click="toggleVisibility(comment.id, index)" 
                                                :class="comment.is_hidden ? 'text-green-500 hover:text-green-700' : 'text-red-500 hover:text-red-700'"
                                                class="p-1 rounded hover:bg-gray-100">
                                            <svg x-show="comment.is_hidden" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <svg x-show="!comment.is_hidden" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>                                
                                <!-- Mode édition -->
                                <div x-show="comment.editing" class="mt-2">
                                    <div class="mb-2">
                                        <label class="block mb-1">Note</label>
                                        <div class="flex space-x-1">
                                            <template x-for="i in 5">
                                                <button @click="comment.editNote = i" 
                                                        class="text-2xl focus:outline-none"
                                                        :class="comment.editNote >= i ? 'text-yellow-400' : 'text-gray-300'">★</button>
                                            </template>
                                        </div>
                                    </div>
                                    <textarea x-model="comment.editContenu" 
                                            class="w-full p-2 border rounded mb-2" 
                                            rows="3"></textarea>
                                    <div class="flex space-x-2">
                                        <button @click="updateComment(comment.id, index)" 
                                                class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600">
                                            Enregistrer
                                        </button>
                                        <button @click="cancelEdit(index)" 
                                                class="bg-gray-500 text-white py-1 px-3 rounded hover:bg-gray-600">
                                            Annuler
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Message si aucun commentaire -->
                        <div x-show="commentaires.length === 0" class="text-gray-500 text-center py-4">
                            Aucun commentaire pour le moment. Soyez le premier à partager votre expérience avec ce guide !
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts Alpine.js et composant commentaires -->
<script>
    function commentairesComponent() {
        return {
            commentaires: [],
            noteMoyenne: 0,    
            totalCommentaires: 0,
            canComment: false,
            isLoggedIn: false,
            userId: null,
            isAdmin: false,
            userHasCommented: false,
            submitting: false,
            errorMessage: '',
            commentableType: '', // 'guide' dans ce cas
            commentableId: null,
            newComment: {
                contenu: '',
                note: 0
            },
            
            init() {
                // Récupérer les données de l'élément à commenter
                this.commentableType = document.querySelector('meta[name="commentable-type"]').getAttribute('content');
                this.commentableId = document.querySelector('meta[name="commentable-id"]').getAttribute('content');
                
                // Vérifier si l'utilisateur est connecté
                const userMetaElement = document.querySelector('meta[name="user-id"]');
                this.isLoggedIn = userMetaElement !== null;
                
                if (this.isLoggedIn) {
                    this.userId = parseInt(userMetaElement.getAttribute('content'));
                    this.isAdmin = document.querySelector('meta[name="user-role"]').getAttribute('content') === 'Administrateur';
                }
                
                this.loadCommentaires();
                
                if (this.isLoggedIn) {
                    this.checkUserCanComment();
                }
            },
            
            loadCommentaires() {
                fetch(`/commentaires?commentable_type=${this.commentableType}&commentable_id=${this.commentableId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.commentaires = data.commentaires.map(comment => ({
                                ...comment,
                                editing: false,
                                editContenu: comment.contenu,
                                editNote: comment.note
                            }));
                            this.noteMoyenne = data.note_moyenne;
                            this.totalCommentaires = data.total_commentaires;
                            
                            // Vérifier si l'utilisateur a déjà commenté
                            if (this.isLoggedIn) {
                                this.userHasCommented = this.commentaires.some(comment => comment.user_id === parseInt(this.userId));
                                this.canComment = !this.userHasCommented;
                            }
                        }
                    })
                    .catch(error => console.error('Erreur lors du chargement des commentaires:', error));
            },
            
            checkUserCanComment() {
                // Cette vérification sera effectuée côté serveur
                fetch(`/commentaires/can-comment?commentable_type=${this.commentableType}&commentable_id=${this.commentableId}`, {
                    headers: {
                        'Authorization': `Bearer ${this.getToken()}`
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        this.canComment = data.can_comment && !this.userHasCommented;
                    })
                    .catch(error => console.error('Erreur lors de la vérification des droits:', error));
            },
            
            submitComment() {
                if (this.newComment.contenu.trim() === '') {
                    this.errorMessage = 'Veuillez écrire un commentaire';
                    return;
                }
                
                if (this.newComment.note === 0) {
                    this.errorMessage = 'Veuillez donner une note';
                    return;
                }
                
                this.submitting = true;
                this.errorMessage = '';
                
                fetch('/commentaires', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${this.getToken()}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        contenu: this.newComment.contenu,
                        note: this.newComment.note,
                        commentable_type: this.commentableType,
                        commentable_id: this.commentableId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        this.submitting = false;
                        
                        if (data.success) {
                            // Ajouter le commentaire à la liste
                            const newComment = {
                                ...data.commentaire,
                                editing: false,
                                editContenu: data.commentaire.contenu,
                                editNote: data.commentaire.note
                            };
                            
                            this.commentaires.unshift(newComment);
                            this.userHasCommented = true;
                            this.canComment = false;
                            
                            // Réinitialiser le formulaire
                            this.newComment.contenu = '';
                            this.newComment.note = 0;
                            
                            // Mettre à jour les statistiques
                            this.totalCommentaires++;
                            this.updateAverageRating();
                        } else {
                            this.errorMessage = data.message;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de l\'ajout du commentaire:', error);
                        this.submitting = false;
                        this.errorMessage = 'Une erreur est survenue lors de l\'ajout du commentaire';
                    });
            },
            
            editComment(index) {
                this.commentaires[index].editing = true;
            },
            
            cancelEdit(index) {
                this.commentaires[index].editing = false;
                this.commentaires[index].editContenu = this.commentaires[index].contenu;
                this.commentaires[index].editNote = this.commentaires[index].note;
            },
            
            updateComment(commentId, index) {
                const comment = this.commentaires[index];
                
                if (comment.editContenu.trim() === '') {
                    return;
                }
                
                fetch(`/commentaires/${commentId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${this.getToken()}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        contenu: comment.editContenu,
                        note: comment.editNote
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.commentaires[index].contenu = comment.editContenu;
                            this.commentaires[index].note = comment.editNote;
                            this.commentaires[index].editing = false;
                            
                            // Mettre à jour la note moyenne
                            this.updateAverageRating();
                        }
                    })
                    .catch(error => console.error('Erreur lors de la mise à jour du commentaire:', error));
            },
            
            deleteComment(commentId) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
                    return;
                }
                
                fetch(`/commentaires/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${this.getToken()}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Supprimer le commentaire de la liste
                            this.commentaires = this.commentaires.filter(comment => comment.id !== commentId);
                            this.userHasCommented = false;
                            this.canComment = true;
                            
                            // Mettre à jour les statistiques
                            this.totalCommentaires--;
                            this.updateAverageRating();
                        }
                    })
                    .catch(error => console.error('Erreur lors de la suppression du commentaire:', error));
            },
            
            toggleVisibility(commentId, index) {
                fetch(`/commentaires/${commentId}/visibility`, {
                    method: 'PATCH',
                    headers: {
                        'Authorization': `Bearer ${this.getToken()}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mettre à jour le statut de visibilité
                            this.commentaires[index].is_hidden = data.is_hidden;
                        }
                    })
                    .catch(error => console.error('Erreur lors du changement de visibilité:', error));
            },
            
            updateAverageRating() {
                // Recalculer la note moyenne
                if (this.commentaires.length > 0) {
                    const sum = this.commentaires.reduce((acc, comment) => acc + comment.note, 0);
                    this.noteMoyenne = (sum / this.commentaires.length).toFixed(1);
                } else {
                    this.noteMoyenne = 0;
                }
            },
            
            formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
            },
            
            getToken() {
                // Récupérer le token d'authentification depuis localStorage ou sessionStorage
                return localStorage.getItem('auth_token') || '';
            }
        };
    }
</script>
@endsection