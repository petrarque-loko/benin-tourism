@extends('layouts.app')

@section('title', 'Détails de la réservation')

@section('content')
<div class="py-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8" 
     x-data="{ 
        activeTab: 'details',
        showDetails: true,
        animate: false,
        init() {
            this.animate = true;
            this.setupConfetti();
            this.setupAnimations();
        },
        setupConfetti() {
            if ('{{ $reservation->statut }}' === 'approuvé' && '{{ $reservation->statut_paiement }}' === 'paye') {
                setTimeout(() => {
                    this.launchConfetti();
                }, 500);
            }
        },
        launchConfetti() {
            const confettiSettings = { target: 'confetti-canvas', max: 150, size: 1.5, animate: true, props: ['circle', 'square', 'triangle', 'line'], colors: [[165,104,246],[230,61,135],[0,199,228],[253,214,126]], clock: 25, rotate: true };
            const confetti = new ConfettiGenerator(confettiSettings);
            confetti.render();
            setTimeout(() => {
                confetti.clear();
            }, 3000);
        },
        setupAnimations() {
            this.observeElements();
        },
        observeElements() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, { threshold: 0.1 });
            
            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        },
        changeTab(tab) {
            this.showDetails = false;
            setTimeout(() => {
                this.activeTab = tab;
                this.showDetails = true;
            }, 300);
        }
     }"
     class="transition-all duration-500 ease-in-out"
     :class="animate ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform translate-y-4'"
>
    <!-- Confetti Canvas pour les réservations confirmées et payées -->
    <canvas id="confetti-canvas" class="fixed inset-0 z-50 pointer-events-none"></canvas>

    <div class="flex justify-between items-center mb-8">
        <div class="animate-on-scroll opacity-0 transform translate-y-4 transition-all duration-500 ease-in-out">
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Détails de la réservation</h1>
            <p class="text-gray-500">Référence #{{ $reservation->id }}</p>
        </div>
        <a href="{{ route('touriste.reservations.circuits.index') }}" 
           class="animate-on-scroll opacity-0 transform translate-y-4 transition-all duration-500 ease-in-out inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300"
        >
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>

    @if(session('success'))
    <div 
        class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 p-5 mb-8 rounded-r shadow-md animate-on-scroll opacity-0 transform translate-y-4 transition-all duration-500 ease-in-out"
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        x-init="setTimeout(() => show = false, 5000)"
    >
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex justify-between w-full">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white shadow-xl rounded-xl overflow-hidden animate-on-scroll opacity-0 transform translate-y-4 transition-all duration-500 ease-in-out">
        <!-- Status Banner -->
        <div class="px-6 py-4 {{ $reservation->statut === 'annulee' ? 'bg-gradient-to-r from-red-50 to-red-100' : ($reservation->statut === 'approuvé' ? 'bg-gradient-to-r from-green-50 to-emerald-100' : 'bg-gradient-to-r from-yellow-50 to-amber-100') }} flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 animate-pulse">
                    @if($reservation->statut === 'annulee')
                        <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    @elseif($reservation->statut === 'approuvé')
                        <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </div>
                <div class="ml-3">
                    <h3 class="text-base font-medium {{ $reservation->statut === 'annulee' ? 'text-red-800' : ($reservation->statut === 'approuvé' ? 'text-green-800' : 'text-yellow-800') }}">
                        Statut: 
                        <span class="font-bold">
                            @if($reservation->statut === 'en_attente')
                                En attente de confirmation
                            @elseif($reservation->statut === 'approuvé')
                                Confirmée
                            @elseif($reservation->statut === 'annulee')
                                Annulée
                            @else
                                {{ ucfirst($reservation->statut) }}
                            @endif
                        </span>
                    </h3>
                    @if($reservation->statut === 'annulee' && $reservation->raison_annulation)
                        <div class="mt-1 text-sm {{ $reservation->statut === 'annulee' ? 'text-red-700' : '' }}">
                            <p>Raison: {{ $reservation->raison_annulation }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($reservation->statut_paiement === 'paye')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg class="mr-1.5 h-3 w-3 text-green-500" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    Payé
                </span>
            @elseif($reservation->statut_paiement === 'en_attente')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <svg class="mr-1.5 h-3 w-3 text-yellow-500" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    Paiement en attente
                </span>
            @endif
        </div>

        <!-- Tabs -->
        <div class="relative border-b border-gray-200">
            <div class="px-4 sm:px-6">
                <nav class="flex space-x-8">
                    <button 
                        @click="changeTab('details')" 
                        class="py-4 px-1 text-sm font-medium border-b-2 focus:outline-none transition-all duration-300 ease-in-out relative" 
                        :class="activeTab === 'details' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    >
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="activeTab === 'details' ? 'text-indigo-500' : 'text-gray-400'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span>Détails</span>
                        </div>
                        <span 
                            x-show="activeTab === 'details'"
                            class="absolute bottom-0 inset-x-0 h-0.5 bg-indigo-500 transform transition-transform duration-300"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="scale-x-0"
                            x-transition:enter-end="scale-x-100"
                        ></span>
                    </button>
                    <button 
                        @click="changeTab('circuit')" 
                        class="py-4 px-1 text-sm font-medium border-b-2 focus:outline-none transition-all duration-300 ease-in-out relative" 
                        :class="activeTab === 'circuit' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    >
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="activeTab === 'circuit' ? 'text-indigo-500' : 'text-gray-400'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <span>Circuit</span>
                        </div>
                        <span 
                            x-show="activeTab === 'circuit'"
                            class="absolute bottom-0 inset-x-0 h-0.5 bg-indigo-500 transform transition-transform duration-300"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="scale-x-0"
                            x-transition:enter-end="scale-x-100"
                        ></span>
                    </button>
                    <button 
                        @click="changeTab('guide')" 
                        class="py-4 px-1 text-sm font-medium border-b-2 focus:outline-none transition-all duration-300 ease-in-out relative" 
                        :class="activeTab === 'guide' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    >
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="activeTab === 'guide' ? 'text-indigo-500' : 'text-gray-400'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Guide</span>
                        </div>
                        <span 
                            x-show="activeTab === 'guide'"
                            class="absolute bottom-0 inset-x-0 h-0.5 bg-indigo-500 transform transition-transform duration-300"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="scale-x-0"
                            x-transition:enter-end="scale-x-100"
                        ></span>
                    </button>
                    <button 
                        @click="changeTab('actions')" 
                        class="py-4 px-1 text-sm font-medium border-b-2 focus:outline-none transition-all duration-300 ease-in-out relative" 
                        :class="activeTab === 'actions' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    >
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="activeTab === 'actions' ? 'text-indigo-500' : 'text-gray-400'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <span>Actions</span>
                        </div>
                        <span 
                            x-show="activeTab === 'actions'"
                            class="absolute bottom-0 inset-x-0 h-0.5 bg-indigo-500 transform transition-transform duration-300"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="scale-x-0"
                            x-transition:enter-end="scale-x-100"
                        ></span>
                    </button>
                </nav>
            </div>
            
            <!-- Animated Indicator -->
            <div class="absolute bottom-0 w-1/4 h-0.5 bg-indigo-500 transition-all duration-300 ease-in-out"
                 :class="{
                    'left-0': activeTab === 'details',
                    'left-1/4': activeTab === 'circuit',
                    'left-2/4': activeTab === 'guide',
                    'left-3/4': activeTab === 'actions'
                 }">
            </div>
        </div>

        <!-- Tab content with transitions -->
        <div class="relative overflow-hidden">
            <!-- Détails Tab -->
            <div 
                x-show="activeTab === 'details' && showDetails" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-4"
                class="px-6 py-6"
            >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="animate-on-scroll opacity-0 transform translate-y-4 transition-all duration-500 ease-in-out">
                        <h3 class="text-lg font-bold leading-6 text-gray-900 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informations de réservation
                        </h3>
                        
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-inner">
                            <div class="mb-6 flex items-start">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Référence</p>
                                    <p class="mt-1 text-base font-bold text-gray-900">#{{ $reservation->id }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-6 flex items-start">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Dates</p>
                                    <p class="mt-1 text-base font-bold text-gray-900">
                                        Du <span class="text-indigo-600">{{ $reservation->date_debut->format('d/m/Y') }}</span> au <span class="text-indigo-600">{{ $reservation->date_fin->format('d/m/Y') }}</span>
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $reservation->date_debut->diffInDays($reservation->date_fin) + 1 }} jours
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mb-6 flex items-start">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nombre de personnes</p>
                                    <p class="mt-1 text-base font-bold text-gray-900">{{ $reservation->nombre_personnes }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Réservé le</p>
                                    <p class="mt-1 text-base font-bold text-gray-900">{{ $reservation->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="animate-on-scroll opacity-0 transform translate-y-4 transition-all duration-500 ease-in-out">
                        <h3 class="text-lg font-bold leading-6 text-gray-900 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Paiement
                        </h3>
                        
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-inner">
                            <div class="mb-6">
                                <p class="text-sm font-medium text-gray-500">Statut du paiement</p>
                                <div class="mt-2">
                                    @if($reservation->statut_paiement === 'paye')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="-ml-1 mr-1.5 h-4 w-4 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Payé
                                        </span>
                                    @elseif($reservation->statut_paiement === 'en_attente')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="-ml-1 mr-1.5 h-4 w-4 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                            En attente
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($reservation->statut_paiement) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($reservation->reference_paiement)
                            <div class="mb-6 flex items-start">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Référence de paiement</p>
                                    <p class="mt-1 text-base font-bold text-gray-900">{{ $reservation->reference_paiement }}</p>
                                </div>
                            </div>
                            @endif
                            
                            <div class="mb-6">
                                <p class="text-sm font-medium text-gray-500">Montant total</p>
                                <p class="mt-1 text-xl font-bold text-gray-900">{{ number_format($reservation->reservable->prix * $reservation->nombre_personnes, 2) }} €</p>
                            </div>
                            
                            @if($reservation->statut !== 'annulee' && $reservation->statut_paiement !== 'paye')
                            <div class="mt-6">
                                <a href="#" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300">
                                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    Procéder au paiement
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Circuit Tab -->
            <div 
                x-show="activeTab === 'circuit' && showDetails" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-4"
                class="px-6 py-6"
            >
                <div class="animate-on-scroll opacity-0 transform translate-y-4 transition-all duration-500 ease-in-out">
                    <div class="flex items-center mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $reservation->reservable->nom }}</h3>
                            <p class="text-sm text-gray-500">{{ $reservation->reservable->localisation }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informations
                            </h4>
                            
                            <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
                                <ul class="divide-y divide-gray-200">
                                    <li class="px-4 py-3 flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-500">Durée</span>
                                        <span class="text-sm text-gray-900">{{ $reservation->reservable->duree }} jours</span>
                                    </li>
                                    <li class="px-4 py-3 flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-500">Niveau de difficulté</span>
                                        <span class="text-sm text-gray-900">{{ $reservation->reservable->difficulte }}</span>
                                    </li>
                                    <li class="px-4 py-3 flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-500">Prix par personne</span>
                                        <span class="text-sm font-bold text-green-600">{{ number_format($reservation->reservable->prix, 2, ',', ' ') }} €</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Description
                            </h4>
                            
                            <div class="bg-white shadow-sm rounded-xl p-4 border border-gray-200">
                                <p class="text-sm text-gray-600 line-clamp-6">{{ $reservation->reservable->description }}</p>
                                <a href="{{ route('circuits.show', $reservation->reservable->id ) }}" class="mt-2 text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors">Voir plus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Guide Tab -->
            <div 
                x-show="activeTab === 'guide' && showDetails" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-4"
                class="px-6 py-6"
            >
                <div class="animate-on-scroll opacity-0 transform translate-y-4 transition-all duration-500 ease-in-out">
                    <div class="flex flex-col sm:flex-row items-center bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200 p-6">
                        <div class="mb-4 sm:mb-0 sm:mr-6 flex-shrink-0">
                            <img src="{{ $reservation->guide->photo ? asset('storage/' . $reservation->guide->photo) : asset('images/3.jpeg') }}" 
                                 alt="{{ $reservation->guide->nom }}" 
                                 class="h-24 w-24 rounded-full object-cover shadow-md border-2 border-indigo-100">
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">
                                <a href="{{route('users.show',$reservation->guide->id)}}">{{ $reservation->guide->nom }} {{ $reservation->guide->prenom }}</a>
                            </h3>
                            <p class="text-sm text-gray-500 mb-3">{{ $reservation->guide->email }}</p>
                            <p class="text-sm text-gray-600 mb-3">{{ $reservation->guide->description }}</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    Guide certifié
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    8 ans d'expérience
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Français-Anglais
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($reservation->statut === 'approuvé')
                    <div class="mt-6 bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
                        <div class="p-4 bg-indigo-50 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                Contacter le guide
                            </h4>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="objet" class="block text-sm font-medium text-gray-700 mb-1">Objet</label>
                                    <input type="text" id="objet" name="objet" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Objet de votre message">
                                </div>
                                <div>
                                    <label for="method" class="block text-sm font-medium text-gray-700 mb-1">Méthode de contact préférée</label>
                                    <select id="method" name="method" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="email">Email</option>
                                        <option value="telephone">Téléphone</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea id="message" name="message" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Écrivez votre message ici..."></textarea>
                            </div>
                            <div class="mt-4">
                                <button type="button" class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Envoyer le message
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Actions Tab -->
            <div 
                x-show="activeTab === 'actions' && showDetails" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-4"
                class="px-6 py-6"
            >
                <div class="animate-on-scroll opacity-0 transform translate-y-4 transition-all duration-500 ease-in-out">
                    @if($reservation->statut !== 'annulee')
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold leading-6 text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                Actions
                            </h3>
                        </div>
                        
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if($reservation->statut === 'en_attente')
                            <a href="{{ route('touriste.reservations.circuits.edit', $reservation->id) }}" 
                               class="inline-flex justify-center items-center px-4 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier la réservation
                            </a>
                            @endif
                            
                            <a href="{{ route('touriste.reservations.circuits.confirm-cancel', $reservation->id) }}"
                               class="inline-flex justify-center items-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300">
                                <svg class="h-5 w-5 mr-2 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Annuler la réservation
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/confetti-js@0.0.18/dist/index.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des éléments au scroll
        const animateOnScroll = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-4');
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            animateOnScroll.observe(el);
        });
    });
</script>
@endpush

@endsection