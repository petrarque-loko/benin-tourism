<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Plateforme Touristique du Bénin')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite('resources/css/app.css')
    
    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    
    @vite('resources/js/app.js')
    
    @stack('styles')
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50">
    <div x-data="{ menuOpen: false, userMenuOpen: false, scrolled: false }" 
         @scroll.window="scrolled = window.pageYOffset > 20">
        <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
                :class="{ 'bg-white/95 backdrop-blur-sm shadow-md': scrolled, 'bg-white shadow-sm': !scrolled }">
            <!-- Navigation -->
            <nav class="container mx-auto px-4 py-3 flex justify-between items-center">
                <a href="/" class="font-bold text-2xl text-emerald-600 flex items-center">
                    <!-- Ajouter logo si disponible -->
                    <span class="ml-2">BéninTourisme</span>
                </a>
                
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('sites.index') }}" class="text-gray-700 hover:text-emerald-600 transition-colors relative group">
                        Sites touristiques
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('evenements.index') }}" class="text-gray-700 hover:text-emerald-600 transition-colors relative group">
                        Événements Culturels
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('traditions_coutumes.index') }}" class="text-gray-700 hover:text-emerald-600 transition-colors relative group">
                        Nos traditions
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('chambres.index') }}" class="text-gray-700 hover:text-emerald-600 transition-colors relative group">
                        Hébergements
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('circuits.index') }}" class="text-gray-700 hover:text-emerald-600 transition-colors relative group">
                        Circuits
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-emerald-600 transition-colors relative group">
                        À propos
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('contact.index') }}" class="text-gray-700 hover:text-emerald-600 transition-colors relative group">
                        Contact
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-emerald-600 transition-colors">Connexion</a>
                        <a href="{{ route('register') }}" 
                           class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors transform hover:scale-105 duration-300 shadow-md">
                            Inscription
                        </a>
                    @else
                        <div class="relative">
                            <button @click="userMenuOpen = !userMenuOpen" 
                                    @click.away="userMenuOpen = false"
                                    class="flex items-center space-x-2 focus:outline-none">
                                <!-- Image de profil (facultatif) -->
                                <div class="w-8 h-8 rounded-full bg-emerald-200 flex items-center justify-center text-emerald-700">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                     :class="{'rotate-180': userMenuOpen}"
                                     class="h-5 w-5 transition-transform duration-300" 
                                     viewBox="0 0 20 20" 
                                     fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <div x-show="userMenuOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5">
                                
                                @if(Auth::user()->role_id == 10) <!-- Guide -->
                                    <a href="{{ route('guide.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            Dashboard
                                        </div>
                                    </a>
                                    <a href="{{ route('guide.reservations') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Mes réservations
                                        </div>
                                    </a>
                                @elseif(Auth::user()->role_id == 12) <!-- Touriste -->
                                    <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            Dashboard
                                        </div>
                                    </a>
                                    <a href="{{ route('touriste.reservations.sites.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Mes réservations
                                        </div>
                                    </a>
                                @endif
                                
                                <a href="{{ route('users.show', Auth::user()->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Mon profil
                                    </div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Paramètres
                                    </div>
                                </a>
                                
                                <hr class="my-1">
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Déconnexion
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
                
                <!-- Menu mobile amélioré -->
                <div class="md:hidden">
                    <button @click="menuOpen = !menuOpen" class="text-gray-700 focus:outline-none">
                        <svg x-show="!menuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="menuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </nav>
            
            <!-- Menu mobile - séparé de la navigation principale pour un meilleur contrôle -->
            <div x-show="menuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="absolute top-16 inset-x-0 bg-white shadow-lg rounded-b-lg p-4 z-50 md:hidden">
                
                <div class="flex flex-col space-y-3">
                    <a href="{{ route('sites.index') }}" 
                       @click="menuOpen = false"
                       class="flex items-center text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 p-2 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Sites touristiques
                    </a>
                    <a href="{{ route('evenements.index') }}" 
                       @click="menuOpen = false"
                       class="flex items-center text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 p-2 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Événements Culturels
                    </a>
                    <a href="{{ route('traditions_coutumes.index') }}" 
                       @click="menuOpen = false"
                       class="flex items-center text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 p-2 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                        Nos traditions et coutumes
                    </a>
                    <a href="{{ route('chambres.index') }}" 
                       @click="menuOpen = false"
                       class="flex items-center text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 p-2 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Hébergements
                    </a>
                    <a href="{{ route('circuits.index') }}" 
                       @click="menuOpen = false"
                       class="flex items-center text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 p-2 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Circuits Touristiques
                    </a>
                    <a href="{{ route('about') }}" 
                       @click="menuOpen = false"
                       class="flex items-center text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 p-2 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        À propos
                    </a>
                    <a href="{{ route('contact.index') }}" 
                       @click="menuOpen = false"
                       class="flex items-center text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 p-2 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Contact
                    </a>
                    
                    @guest
                        <div class="pt-3 border-t border-gray-200 grid grid-cols-2 gap-2">
                            <a href="{{ route('login') }}" 
                               @click="menuOpen = false"
                               class="flex justify-center items-center py-2 text-center text-emerald-600 border border-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" 
                               @click="menuOpen = false"
                               class="flex justify-center items-center py-2 text-center bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Inscription
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        </header>

        <!-- Espaceur pour compenser le header fixe -->
        <div class="pt-16"></div>

        <main x-data="{ showScrollTop: false }" @scroll.window="showScrollTop = window.pageYOffset > 500" style="background-image: url('/images/background.jpg')">
            @yield('content')
            
            <!-- Bouton retour en haut -->
            <button x-show="showScrollTop" 
                    @click="window.scrollTo({top: 0, behavior: 'smooth'})" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90"
                    class="fixed right-6 bottom-6 p-3 rounded-full bg-emerald-600 text-white shadow-lg hover:bg-emerald-700 focus:outline-none transform hover:scale-110 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </main>

        <footer class="bg-gray-800 text-white">
            <div x-data="{ expanded: { about: false, links: false, contact: false } }" class="container mx-auto px-4 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="md:hidden flex justify-between items-center mb-2">
                            <h3 class="text-lg font-bold">À propos</h3>
                            <button @click="expanded.about = !expanded.about" class="text-white">
                                <svg x-show="!expanded.about" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <svg x-show="expanded.about" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <h3 class="text-lg font-bold hidden md:block mb-4">À propos</h3>
                        <div x-show="expanded.about || window.innerWidth >= 768" class="space-y-3">
                            <a href="{{ route('about') }}" class="block text-gray-300 hover:text-white transition-colors">Notre mission</a>
                            <a href="#" class="block text-gray-300 hover:text-white transition-colors">Équipe</a>
                            <a href="#" class="block text-gray-300 hover:text-white transition-colors">Histoire</a>
                            <a href="#" class="block text-gray-300 hover:text-white transition-colors">Témoignages</a>
                        </div>
                    </div>
                    
                    <div>
                        <div class="md:hidden flex justify-between items-center mb-2">
                            <h3 class="text-lg font-bold">Liens utiles</h3>
                            <button @click="expanded.links = !expanded.links" class="text-white">
                                <svg x-show="!expanded.links" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <svg x-show="expanded.links" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <h3 class="text-lg font-bold hidden md:block mb-4">Liens utiles</h3>
                        <div x-show="expanded.links || window.innerWidth >= 768" class="space-y-3">
                            <a href="{{ route('sites.index') }}" class="block text-gray-300 hover:text-white transition-colors">Sites touristiques</a>
                            <a href="{{ route('evenements.index') }}" class="block text-gray-300 hover:text-white transition-colors">Événements</a>
                            <a href="{{ route('circuits.index') }}" class="block text-gray-300 hover:text-white transition-colors">Circuits</a>
                            <a href="{{ route('chambres.index') }}" class="block text-gray-300 hover:text-white transition-colors">Hébergements</a>
                        </div>
                    </div>
                    
                    <div>
                        <div class="md:hidden flex justify-between items-center mb-2">
                            <h3 class="text-lg font-bold">Contact</h3>
                            <button @click="expanded.contact = !expanded.contact" class="text-white">
                                <svg x-show="!expanded.contact" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <svg x-show="expanded.contact" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <h3 class="text-lg font-bold hidden md:block mb-4">Contact</h3>
                        <div x-show="expanded.contact || window.innerWidth >= 768" class="space-y-3">
                            <a href="{{ route('contact.index') }}" class="block text-gray-300 hover:text-white transition-colors">Nous contacter</a>
                            <p class="text-gray-300">
                                <span class="block">Cotonou, Bénin</span>
                                <span class="block">+229 xx xx xx xx</span>
                                <span class="block">info@benintourisme.com</span>
                            </p>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold mb-4">Newsletter</h3>
                        <p class="text-gray-300 mb-4">Recevez nos actualités et offres spéciales</p>
                        <form action="#" method="POST" class="space-y-3">
                            <div>
                                <input type="email" name="email" placeholder="Votre email" required
                                       class="w-full px-3 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <button type="submit" 
                                    class="w-full bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors transform hover:scale-105 duration-300">
                                S'abonner
                            </button>
                        </form>
                        
                        <div class="mt-6">
                            <h4 class="text-md font-semibold mb-3">Suivez-nous</h4>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                    <!-- Facebook Icon -->
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                    <!-- Twitter/X Icon -->
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.896-.959-2.173-1.559-3.591-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124-4.09-.193-7.715-2.157-10.141-5.126-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 14-7.503 14-14 0-.21-.005-.42-.014-.629.961-.689 1.8-1.56 2.46-2.548z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                    <!-- Instagram Icon -->
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                    <!-- YouTube Icon -->
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-12 pt-8 border-t border-gray-700 text-center">
                    <p class="text-gray-400">
                        &copy; {{ date('Y') }} BéninTourisme. Tous droits réservés.
                    </p>
                    <div class="flex justify-center mt-4 space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Conditions d'utilisation</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Politique de confidentialité</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Mentions légales</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
    
    <!-- Notifications -->
    <div x-data="{ notifications: [] }" 
         class="fixed top-20 right-4 z-50 w-full max-w-sm space-y-3">
        
        <!-- Notification Template -->
        <template x-for="(notification, index) in notifications" :key="index">
            <div x-show="notification.visible" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-8"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform translate-x-8"
                 class="relative px-4 py-3 rounded-lg shadow-lg" 
                 :class="{
                     'bg-emerald-500 text-white': notification.type === 'success',
                     'bg-red-500 text-white': notification.type === 'error',
                     'bg-yellow-500 text-white': notification.type === 'warning',
                     'bg-blue-500 text-white': notification.type === 'info'
                 }">
                <div class="flex items-center">
                    <div class="mr-3" 
                         :class="{
                             'text-emerald-200': notification.type === 'success',
                             'text-red-200': notification.type === 'error',
                             'text-yellow-200': notification.type === 'warning',
                             'text-blue-200': notification.type === 'info'
                         }">
                        <!-- Success Icon -->
                        <svg x-show="notification.type === 'success'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <!-- Error Icon -->
                        <svg x-show="notification.type === 'error'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <!-- Warning Icon -->
                        <svg x-show="notification.type === 'warning'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <!-- Info Icon -->
                        <svg x-show="notification.type === 'info'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div x-text="notification.message" class="font-medium"></div>
                    </div>
                </div>
                <button @click="notification.visible = false" class="absolute top-1 right-1 text-white hover:text-gray-200">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>
    
    <!-- Script pour les notifications flash -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Alpine !== 'undefined') {
                window.showNotification = function(message, type = 'info', duration = 5000) {
                    const notification = {
                        message: message,
                        type: type,
                        visible: true
                    };
                    
                    // Find the notifications component
                    const notificationsEl = document.querySelector('[x-data*="notifications"]');
                    if (notificationsEl) {
                        const notificationsComponent = Alpine.$data(notificationsEl);
                        notificationsComponent.notifications.push(notification);
                        
                        setTimeout(() => {
                            notification.visible = false;
                            
                            // Remove from array after animation
                            setTimeout(() => {
                                const index = notificationsComponent.notifications.indexOf(notification);
                                if (index > -1) {
                                    notificationsComponent.notifications.splice(index, 1);
                                }
                            }, 300);
                        }, duration);
                    }
                };
                
                // Flash messages de Laravel
                @if(session('success'))
                    window.showNotification("{{ session('success') }}", 'success');
                @endif
                
                @if(session('error'))
                    window.showNotification("{{ session('error') }}", 'error');
                @endif
                
                @if(session('warning'))
                    window.showNotification("{{ session('warning') }}", 'warning');
                @endif
                
                @if(session('info'))
                    window.showNotification("{{ session('info') }}", 'info');
                @endif
            }
        });
    </script>
</body>
</html>