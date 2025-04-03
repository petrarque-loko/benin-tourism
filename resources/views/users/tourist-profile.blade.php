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



<div class="bg-cover bg-center bg-fixed min-h-screen py-6" 
     style="background-image: url('/images/background.jpg');">
    <div 
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
        </div>
    </div>
</div>
@endsection