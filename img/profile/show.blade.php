@extends('layouts.app')

@section('content')
<div 


<div class="bg-cover bg-center bg-fixed min-h-screen py-6" 
     style="background-image: url('/images/background.jpg');">
    <div " 
         x-data="siteEditor()">
        <!-- Reste du contenu... -->
    </div>

    <div class="max-w-3xl mx-auto mt-20">
        <!-- Section Profil -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 text-white px-6 py-5">
                <h2 class="text-2xl font-semibold flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Mon Profil
                </h2>
            </div>
            
            <div class="p-6" x-data="{ 
                hasSuccess: @json(session('success') !== null),
                showSuccessMessage: @json(session('success') !== null)
            }">
                <!-- Message de succès amélioré -->
                <div 
                    x-show="showSuccessMessage" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-4"
                    class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between"
                    role="alert"
                >
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="showSuccessMessage = false" class="text-green-700 hover:text-green-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Informations de base avec card design -->
                <div class="mb-8 bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations personnelles</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="flex items-center group">
                            <div class="bg-indigo-100 p-3 rounded-lg mr-3 group-hover:bg-indigo-200 transition-colors duration-200">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Nom</span>
                                <p class="text-gray-900 font-medium">{{ auth()->user()->nom }}</p>
                            </div>
                        </div>
                        <div class="flex items-center group">
                            <div class="bg-indigo-100 p-3 rounded-lg mr-3 group-hover:bg-indigo-200 transition-colors duration-200">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Prénom</span>
                                <p class="text-gray-900 font-medium">{{ auth()->user()->prenom }}</p>
                            </div>
                        </div>
                        <div class="flex items-center group">
                            <div class="bg-indigo-100 p-3 rounded-lg mr-3 group-hover:bg-indigo-200 transition-colors duration-200">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Email</span>
                                <p class="text-gray-900 font-medium">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center group">
                            <div class="bg-indigo-100 p-3 rounded-lg mr-3 group-hover:bg-indigo-200 transition-colors duration-200">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Téléphone</span>
                                <p class="text-gray-900 font-medium">{{ auth()->user()->telephone }}</p>
                            </div>
                        </div>
                        <div class="flex items-center sm:col-span-2 group">
                            <div class="bg-indigo-100 p-3 rounded-lg mr-3 group-hover:bg-indigo-200 transition-colors duration-200">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Adresse</span>
                                <p class="text-gray-900 font-medium">{{ auth()->user()->adresse }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action améliorés -->
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('profile.edit') }}" 
                       class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 flex items-center justify-center shadow-md hover:shadow-lg"
                       aria-label="Modifier le profil">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier le profil
                    </a>
                    <a href="{{ route('profile.password') }}" 
                       class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-gray-400 flex items-center justify-center shadow-md hover:shadow-lg"
                       aria-label="Changer le mot de passe">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Changer le mot de passe
                    </a>
                </div>
            </div>
        </div>

        <!-- Section Notes et Commentaires (pour les guides) -->
        @if(auth()->user()->isGuide())
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mt-8 transform transition-all duration-300 hover:shadow-xl" 
             x-data="{ 
                isAdmin: @json(auth()->user()->isAdmin()),
                expandedComment: null,
                toggleComment(id) {
                    this.expandedComment = this.expandedComment === id ? null : id;
                }
             }">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 text-white px-6 py-5">
                <h2 class="text-2xl font-semibold flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                    Mes notes et commentaires
                </h2>
            </div>
            
            <div class="p-6">
                @if(auth()->user()->commentaires->count() > 0)
                    <!-- Note moyenne avec design visuel amélioré -->
                    <div class="mb-8 bg-indigo-50 p-5 rounded-lg">
                        <h5 class="text-lg font-semibold text-gray-800 mb-2">Statistiques</h5>
                        <div class="flex flex-col md:flex-row md:items-center md:space-x-12">
                            <div class="mb-4 md:mb-0">
                                <span class="block text-sm font-medium text-gray-500 mb-1">Note moyenne</span>
                                <div class="flex items-center">
                                    <span class="text-3xl font-bold text-indigo-600">
                                        {{ number_format(auth()->user()->commentaires->avg('note'), 1) }}
                                    </span>
                                    <span class="text-lg text-gray-400 ml-1">/5</span>
                                </div>
                                <div class="flex space-x-1 mt-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= round(auth()->user()->commentaires->avg('note')) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            
                            <div>
                                <span class="block text-sm font-medium text-gray-500 mb-1">Nombre d'avis</span>
                                <div class="text-2xl font-bold text-gray-700">
                                    {{ auth()->user()->commentaires->count() }}
                                </div>
                            </div>
                            
                            <div class="mt-4 md:mt-0">
                                <span class="block text-sm font-medium text-gray-500 mb-1">Répartition des notes</span>
                                <div class="flex flex-col space-y-1">
                                    @php
                                        $noteDistribution = [
                                            5 => auth()->user()->commentaires->where('note', 5)->count(),
                                            4 => auth()->user()->commentaires->where('note', 4)->count(),
                                            3 => auth()->user()->commentaires->where('note', 3)->count(),
                                            2 => auth()->user()->commentaires->where('note', 2)->count(),
                                            1 => auth()->user()->commentaires->where('note', 1)->count(),
                                        ];
                                        $total = auth()->user()->commentaires->count();
                                    @endphp
                                    
                                    @for($i = 5; $i >= 1; $i--)
                                        @php $percentage = $total > 0 ? ($noteDistribution[$i] / $total) * 100 : 0; @endphp
                                        <div class="flex items-center text-sm">
                                            <span class="w-3">{{ $i }}</span>
                                            <div class="w-32 bg-gray-200 rounded-full h-2 ml-2 overflow-hidden">
                                                <div class="bg-indigo-600 h-full rounded-full" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="ml-2 text-gray-600 text-xs">{{ $noteDistribution[$i] }}</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tous les commentaires</h3>

                    <!-- Liste des commentaires avec animation et interactivité -->
                    <div class="space-y-4">
                        @foreach(auth()->user()->commentaires as $commentaire)
                        <div class="bg-white shadow rounded-lg p-5 transition-all duration-300 hover:shadow-md"
                             :class="{'border-l-4 border-indigo-500': expandedComment === {{ $commentaire->id }}}"
                             x-data="{ 
                                 isHidden: {{ $commentaire->is_hidden ? 'true' : 'false' }},
                                 toggleVisibility() {
                                     this.isHidden = !this.isHidden;
                                     // Ici on pourrait ajouter une requête AJAX pour mettre à jour en base de données
                                 }
                             }">
                            <div class="flex justify-between items-start">
                                <div class="flex items-start space-x-3">
                                    <img src="{{ $commentaire->user->avatar ?? 'https://via.placeholder.com/40' }}" 
                                         alt="Avatar de {{ $commentaire->user->prenom }}" 
                                         class="w-12 h-12 rounded-full border object-cover">
                                    <div>
                                        <strong class="text-gray-900 font-medium">{{ $commentaire->user->prenom }} {{ $commentaire->user->nom }}</strong>
                                        <div class="flex items-center mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $commentaire->note ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                            <small class="text-gray-500 ml-2">{{ $commentaire->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <span class="badge px-3 py-1 rounded-full text-sm font-semibold 
                                    {{ $commentaire->note >= 4 ? 'bg-green-100 text-green-800' : ($commentaire->note >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $commentaire->note }}/5
                                </span>
                            </div>
                            
                            <div>
                                <p class="text-gray-700 mt-3 relative" 
                                   :class="{'line-clamp-2': expandedComment !== {{ $commentaire->id }}, 'opacity-70': isHidden}"
                                   x-data="{ isLong: {{ strlen($commentaire->contenu) > 150 ? 'true' : 'false' }} }">
                                    {{ $commentaire->contenu }}
                                    <button 
                                        x-show="isLong && expandedComment !== {{ $commentaire->id }}"
                                        @click="toggleComment({{ $commentaire->id }})" 
                                        class="text-indigo-600 hover:text-indigo-800 text-sm font-medium ml-1 focus:outline-none">
                                        Lire plus
                                    </button>
                                </p>
                                <button 
                                    x-show="expandedComment === {{ $commentaire->id }}"
                                    @click="expandedComment = null" 
                                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium mt-1 focus:outline-none">
                                    Réduire
                                </button>
                            </div>
                            
                            <div x-show="isHidden" class="flex items-center text-sm text-amber-600 mt-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                                Ce commentaire est masqué
                            </div>

                            <!-- Bouton pour admin avec toggle animation -->
                            <div x-show="isAdmin" class="mt-3 flex justify-end">
                                <button 
                                    @click="toggleVisibility()"
                                    class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-full transition-colors duration-200 flex items-center"
                                    :class="{'text-red-600': !isHidden, 'text-green-600': isHidden}"
                                    aria-label="Masquer/Afficher le commentaire">
                                    <svg x-show="!isHidden" class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                    <svg x-show="isHidden" class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ !$commentaire->is_hidden ? 'Masquer' : 'Afficher' }}
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Vous n'avez pas encore reçu de commentaires.</span>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Styles personnalisés avec animations améliorées -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out;
    }
    
    /* Truncate multiple lines */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
    
    /* Hover effect on cards */
    .hover-lift {
        transition: transform 0.2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        // Vous pouvez ajouter des fonctionnalités Alpine.js personnalisées ici
        Alpine.data('ratings', () => ({
            // Exemple de données pour un futur graphique de notes
            chartData: [
                @if(auth()->user()->isGuide() && auth()->user()->commentaires->count() > 0)
                    @foreach(range(5, 1) as $noteValue)
                        {{ auth()->user()->commentaires->where('note', $noteValue)->count() }},
                    @endforeach
                @else
                    0, 0, 0, 0, 0
                @endif
            ],
            // Fonctions d'animation supplémentaires peuvent être ajoutées ici
        }));
    });
    
    // Script pour les animations d'entrée
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.bg-white.shadow');
        
        // Ajouter un délai pour chaque carte
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate-fade-in');
        });
    });
</script>
@endsection