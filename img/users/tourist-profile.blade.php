@extends('layouts.app')

@section('content')



<div class="bg-cover bg-center bg-fixed min-h-screen py-6" 
     style="background-image: url('/images/background.jpg');">
    <div " 
         x-data="siteEditor()">
        <!-- Reste du contenu... -->
    </div>


    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
        <!-- En-tête du profil avec effet parallaxe -->
        <div class="relative h-48 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 overflow-hidden" x-data="{}" x-on:mousemove="$el.querySelector('.parallax-bg').style.transform = `translate(${$event.clientX * 0.01}px, ${$event.clientY * 0.01}px)`">
            <div class="absolute inset-0 opacity-20 parallax-bg">
                <div class="absolute inset-0 bg-repeat opacity-10" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMTAiIGN5PSIxMCIgcj0iMiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg==');"></div>
            </div>
        </div>

        <div class="relative px-6 pt-16 pb-8 -mt-20">
            <!-- Photo de profil avec animation -->
            <div class="absolute -top-16 left-1/2 transform -translate-x-1/2" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                <div class="rounded-full p-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 shadow-lg overflow-hidden" :class="{ 'animate-pulse': hover }">
                <img src="{{ $user->image ?? '/images/1.png' }}" alt="{{ $user->nom }}" class="w-32 h-32 rounded-full object-cover border-4 border-white">
                </div>
            </div>

            <!-- Informations principales -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">{{ $user->nom }} {{ $user->prenom }}</h1>
                <div class="flex items-center justify-center mt-2 space-x-2">
                    <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 text-sm font-medium inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                        </svg>
                        Touriste
                    </span>
                    <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-sm font-medium inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Inscrit depuis {{ $user->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>

            <!-- Cartes d'information avec effet hover -->
            <div class="grid md:grid-cols-2 gap-6" x-data="{ activeCard: null }">
                <!-- Informations personnelles -->
                <div class="bg-gradient-to-br from-white to-indigo-50 rounded-xl shadow-md p-6 transition-all duration-300 transform hover:scale-105" 
                     x-data="{ showDetails: false }" 
                     @mouseenter="showDetails = true; activeCard = 'personal'" 
                     @mouseleave="showDetails = false; activeCard = null"
                     :class="{ 'ring-2 ring-indigo-400': activeCard === 'personal' }">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold ml-3 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">Informations personnelles</h2>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center p-2 rounded-lg transition-colors" :class="{ 'bg-indigo-50': showDetails }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="font-medium">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-2 rounded-lg transition-colors" :class="{ 'bg-indigo-50': showDetails }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500">Téléphone</p>
                                <p class="font-medium">{{ $user->telephone ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-2 rounded-lg transition-colors" :class="{ 'bg-indigo-50': showDetails }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500">Nationalité</p>
                                <p class="font-medium">{{ $user->nationalite ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques de voyage -->
                <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-md p-6 transition-all duration-300 transform hover:scale-105"
                     x-data="{ showChart: false }"
                     @mouseenter="showChart = true; activeCard = 'stats'"
                     @mouseleave="showChart = false; activeCard = null"
                     :class="{ 'ring-2 ring-purple-400': activeCard === 'stats' }">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold ml-3 bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">Statistiques de voyage</h2>
                    </div>
                    <div class="space-y-3">
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-sm font-medium text-gray-700">Total réservations</p>
                                <p class="text-sm font-bold text-purple-600">{{ $totalReservations ?? 0 }}</p>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-gradient-to-r from-purple-600 to-pink-500 h-2.5 rounded-full transition-all duration-1000 ease-out" 
                                     x-bind:style="showChart ? 'width: {{ min(($totalReservations ?? 0) * 10, 100) }}%' : 'width: 0%'"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-sm font-medium text-gray-700">Sites visités</p>
                                <p class="text-sm font-bold text-purple-600">{{ $sitesVisites ?? 0 }}</p>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2.5 rounded-full transition-all duration-1000 ease-out"
                                     x-bind:style="showChart ? 'width: {{ min(($sitesVisites ?? 0) * 10, 100) }}%' : 'width: 0%'"></div>
                            </div>
                        </div>
                        <div class="pt-4" x-show="showChart" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                            <div class="flex h-24 items-end justify-around pt-2 space-x-1">
                                @for ($i = 1; $i <= 7; $i++)
                                    <div class="bg-gradient-to-t from-purple-500 to-pink-400 rounded-t w-4 transition-all duration-500" 
                                         x-bind:style="showChart ? 'height: {{ rand(15, 85) }}%' : 'height: 0'"></div>
                                @endfor
                            </div>
                            <div class="text-xs text-center text-gray-500 mt-2">Activité des 7 derniers jours</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section badge et progression -->
            <div class="mt-8 bg-gradient-to-br from-white to-rose-50 rounded-xl shadow-md p-6" x-data="{ progress: 0 }" x-init="setTimeout(() => progress = {{ min(($totalReservations ?? 0) * 10 + ($sitesVisites ?? 0) * 5, 100) }}, 500)">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-full bg-rose-100 text-rose-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold ml-3 bg-clip-text text-transparent bg-gradient-to-r from-rose-600 to-pink-600">Progression & Badges</h2>
                </div>

                <!-- Niveau et progression -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Niveau</span>
                            <h3 class="text-xl font-bold text-gray-800">
                                <span x-text="Math.floor(progress / 20) + 1"></span>
                                <span class="text-sm font-normal text-gray-500">Explorer</span>
                            </h3>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-medium text-gray-500">Progression</span>
                            <h3 class="text-xl font-bold text-rose-600" x-text="`${progress}%`"></h3>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-rose-500 to-pink-500 h-3 rounded-full transition-all duration-1000 ease-out" 
                             x-bind:style="`width: ${progress}%`"></div>
                    </div>
                    <div class="flex justify-between mt-1 text-xs text-gray-500">
                        <span>Débutant</span>
                        <span>Expert</span>
                    </div>
                </div>

                <!-- Badges -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Badges débloqués</h3>
                    <div class="grid grid-cols-4 gap-3">
                                            <div class="relative group">
                              
                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 bg-white rounded-lg shadow-lg p-2 text-xs font-medium transition-all duration-200 opacity-0 group-hover:opacity-100 whitespace-nowrap">
                                    <span class="block text-gray-400">Verrouillé</span>
                                    
                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>

            <!-- Réservations récentes -->
            <div class="mt-8" x-data="{ showAll: false }">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">Réservations récentes</h2>
                    <button @click="showAll = !showAll" class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-600 text-sm font-medium hover:bg-indigo-200 transition-colors">
                        <span x-text="showAll ? 'Voir moins' : 'Voir tout'"></span>
                    </button>
                </div>

                @if(isset($reservations) && count($reservations) > 0)
                    <div class="space-y-4">
                        @foreach($reservations as $index => $reservation)
                            <div class="bg-white rounded-xl shadow-md p-4 border border-gray-100 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1"
                                x-show="showAll || {{ $index }} < 3"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full {{ ['bg-indigo-100 text-indigo-600', 'bg-purple-100 text-purple-600', 'bg-rose-100 text-rose-600', 'bg-amber-100 text-amber-600'][$index % 4] }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ ['M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'][$index % 3] }}" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-semibold text-gray-800">{{ $reservation->site->nom ?? 'Site touristique' }}</h3>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $reservation->date_visite->format('d/m/Y') ?? 'Date non définie' }}
                                        </div>
                                    </div>
                                    <div class="ml-auto text-right">
                                        <span class="text-sm font-medium {{ ['text-green-600', 'text-amber-600', 'text-red-600'][$index % 3] }}">
                                            {{ ['Confirmé', 'En attente', 'Terminé'][$index % 3] }}
                                        </span>
                                        <div class="text-sm text-gray-500">
                                            {{ $reservation->nb_personnes ?? rand(1, 4) }} personne{{ ($reservation->nb_personnes ?? rand(1, 4)) > 1 ? 's' : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100 text-center">
                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-1">Aucune réservation</h3>
                        <p class="text-gray-500 mb-4">Vous n'avez pas encore effectué de réservation.</p>
                        <a href="{{ route('sites.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium transition-shadow hover:shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Réserver maintenant
                        </a>
                    </div>
                @endif
            </div>

            <!-- Actions utilisateur -->
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('profile.edit') }}" class="flex-1 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 font-medium flex items-center justify-center hover:bg-indigo-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Modifier mon profil
                </a>
                <a href="{{ route('reservations.index') }}" class="flex-1 px-4 py-3 rounded-xl bg-purple-50 text-purple-600 font-medium flex items-center justify-center hover:bg-purple-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Mes réservations
                </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    Mes favoris
                </a>
            </div>

            <!-- Suggestions personnalisées -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-amber-600 to-yellow-600">Suggestions pour vous</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach(range(1, 2) as $index)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                            <div class="h-32 bg-gradient-to-r {{ ['from-amber-400 to-yellow-500', 'from-teal-400 to-emerald-500'][$index % 2] }} relative">
                                <div class="absolute inset-0 opacity-20" style="background-image: url('https://via.placeholder.com/400x200'); background-size: cover; background-position: center;"></div>
                                <div class="absolute bottom-3 left-3">
                                    <span class="bg-white/80 backdrop-blur-sm text-xs font-medium px-2 py-1 rounded-full text-gray-800">
                                        {{ ['Patrimoine', 'Nature'][$index % 2] }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-gray-800 mb-1">{{ ['Château de Versailles', 'Parc National des Calanques'][$index % 2] }}</h3>
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ ['Versailles, France', 'Marseille, France'][$index % 2] }}
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-800 ml-1">{{ ['4.8', '4.9'][$index % 2] }}</span>
                                    </div>
                                    <a href="#" class="text-sm font-medium text-amber-600 hover:text-amber-700">Voir plus</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection