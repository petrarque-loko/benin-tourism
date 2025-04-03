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
    <div " 
         x-data="siteEditor()">
        <!-- Reste du contenu... -->
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-6xl mx-auto border border-gray-100">
        <!-- Bannière en haut avec gradient -->
        <div class="h-32 bg-gradient-to-r from-indigo-500 to-teal-400	relative">
            <div class="absolute -bottom-12 left-8 ring-4 ring-white shadow-lg rounded-full">
                <img src="{{$user->image ?? '/images/1.png' }}" alt="{{ $user->nom }}" class="w-28 h-28 rounded-full object-cover">

            </div>
        </div>

        <!-- Contenu principal -->
        <div class="pt-16 pb-8 px-8">
            <!-- En-tête avec informations principales -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $user->nom }} {{ $user->prenom }}</h1>
                    <div class="flex items-center mt-2">
                        <span class="inline-flex items-center bg-indigo-100 text-indigo-800 text-sm font-medium px-3 py-1 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Propriétaire d'hébergement
                        </span>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex items-center">
                    <span class="inline-flex items-center text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Inscrit depuis {{ $user->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>

            <!-- Contenu principal en deux colonnes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Colonne gauche -->
                <div>
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 transition-all duration-300 hover:shadow-md">
                        <h2 class="text-xl font-semibold mb-4 flex items-center text-indigo-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informations personnelles
                        </h2>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-medium text-gray-800">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Téléphone</p>
                                    <p class="font-medium text-gray-800">{{ $user->telephone ?? 'Non renseigné' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite -->
                <div>
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 transition-all duration-300 hover:shadow-md" 
                         x-data="{ expanded: false }">
                        <h2 class="text-xl font-semibold mb-4 flex items-center text-indigo-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Hébergements
                            <span class="ml-2 bg-indigo-600 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $hebergements->count() }}</span>
                        </h2>
                        @if($hebergements->count() > 0)
                            <ul class="space-y-3">
                                @foreach($hebergements as $hebergement)
                                    <li class="bg-white rounded-lg p-3 shadow-sm border border-gray-100 transition-all hover:shadow-md">
                                        <a href="{{ route('chambres.index') }}" class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-400 to-purple-400 flex items-center justify-center text-white font-bold">
                                                    {{ substr($hebergement->nom, 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <p class="font-medium text-gray-800">{{ $hebergement->nom }}</p>
                                                    <p class="text-sm text-gray-500">{{ $hebergement->type }}</p>
                                                </div>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <p class="mt-2 text-gray-500">Aucun hébergement proposé actuellement</p>
                                <button class="mt-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-300 flex items-center mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Ajouter un hébergement
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Indicateurs de performance -->
            <div class="mt-8 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 transition-all duration-300 hover:shadow-md">
                <h2 class="text-xl font-semibold mb-4 flex items-center text-indigo-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Statistiques
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ 
                    stats: [
                        { label: 'Taux d'occupation', value: '{{ $hebergements->count() > 0 ? '75%' : '0%' }}' },
                        { label: 'Évaluations', value: '{{ $hebergements->count() > 0 ? '4.8/5' : 'N/A' }}' },
                        { label: 'Réservations', value: '{{ $hebergements->count() > 0 ? '24' : '0' }}' }
                    ]
                }">
                    <template x-for="(stat, index) in stats" :key="index">
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-100">
                            <p class="text-sm text-gray-500" x-text="stat.label"></p>
                            <p class="text-2xl font-bold text-indigo-600" x-text="stat.value"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection