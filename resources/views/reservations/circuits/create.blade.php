@extends('layouts.app')

@section('title', 'Nouvelle réservation de circuit')

@section('content')
<div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" 
    x-data="{ 
        activeSection: 'details',
        animateDetails: true,
        animateForm: false,
        showConfetti: false
    }"
    x-init="
        setTimeout(() => { animateForm = true }, 500);
    ">
    
    <!-- En-tête avec titre et bouton Retour -->
    <div class="flex justify-between items-center mb-6 animate__animated animate__fadeInDown">
        <h1 class="text-2xl font-semibold text-gray-900 relative group">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                Réserver un circuit
            </span>
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-600 to-purple-600 transition-all duration-300 group-hover:w-full"></span>
        </h1>
        
        <!-- Pour les boutons -->
        <a href="{{ route('circuits.index') }}" 
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out transform hover:-translate-y-0.5">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour
        </a>
    </div>

    <!-- Message d'erreur (si présent) -->
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 animate__animated animate__shakeX">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Conteneur principal avec onglets -->
    <div class="bg-white shadow-2xl overflow-hidden sm:rounded-xl border border-gray-100">
        <!-- Navigation par onglets -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button @click="activeSection = 'details'" 
                    class="py-4 px-6 text-center border-b-2 font-medium text-sm transition-all duration-200 ease-in-out"
                    :class="activeSection === 'details' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2" :class="activeSection === 'details' ? 'text-indigo-500' : 'text-gray-400'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Détails du circuit
                    </div>
                </button>
                <button @click="activeSection = 'form'" 
                    class="py-4 px-6 text-center border-b-2 font-medium text-sm transition-all duration-200 ease-in-out"
                    :class="activeSection === 'form' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2" :class="activeSection === 'form' ? 'text-indigo-500' : 'text-gray-400'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Réserver
                    </div>
                </button>
            </nav>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <!-- Détails du circuit avec animations -->
            <div x-show="activeSection === 'details'" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-4">
                <div class="mb-8" :class="{ 'animate__animated animate__fadeIn': animateDetails }">
                    <h2 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Détails du circuit
                    </h2>
                    
                    <div class="mt-6 relative overflow-hidden">
                        <!-- Image du circuit (à ajouter à votre modèle) -->
                        <div class="h-64 w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-xl mb-6 overflow-hidden shadow-lg">
                            <img src="/images/bg3.jpeg" alt="{{ $circuit->nom }}" 
                                class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                        </div>
                        
                        <!-- Carte avec les détails -->
                        <div class="mt-4 border rounded-xl p-5 bg-gradient-to-br from-white to-gray-50 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-white p-4 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md hover:scale-105">
                                    <p class="text-sm font-medium text-gray-500 flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Nom du circuit
                                    </p>
                                    <p class="mt-1 text-xl font-bold text-gray-900">{{ $circuit->nom }}</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md hover:scale-105">
                                    <p class="text-sm font-medium text-gray-500 flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Durée
                                    </p>
                                    <p class="mt-1 text-xl font-bold text-gray-900">
                                        <span class="text-2xl">{{ $circuit->duree }}</span> 
                                        <span class="text-sm font-medium">jour<span x-show="$circuit->duree > 1">s</span></span>
                                    </p>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md hover:scale-105">
                                    <p class="text-sm font-medium text-gray-500 flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Prix
                                    </p>
                                    <p class="mt-1 text-xl font-bold text-indigo-600">{{ number_format($circuit->prix, 2) }} €</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md hover:scale-105">
                                    <p class="text-sm font-medium text-gray-500 flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        Difficulté
                                    </p>
                                    <p class="mt-1">
                                        <span
                                            class="px-2 py-1 text-sm font-medium rounded-full
                                            {{ $circuit->difficulte === 'Facile' ? 'bg-green-100 text-green-800' : 
                                               ($circuit->difficulte === 'Moyen' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ $circuit->difficulte }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-6 bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                                <p class="text-sm font-medium text-gray-500 flex items-center mb-2">
                                    <svg class="h-4 w-4 mr-1 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Description
                                </p>
                                <div class="prose prose-indigo mt-1 text-gray-900 text-sm leading-relaxed">
                                    {{ $circuit->description }}
                                </div>
                            </div>
                            
                            <div class="mt-6 bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                                <p class="text-sm font-medium text-gray-500 flex items-center mb-2">
                                    <svg class="h-4 w-4 mr-1 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Guide
                                </p>
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-3">
                                        {{ substr($circuit->guide->prenom, 0, 1) }}{{ substr($circuit->guide->nom, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $circuit->guide->prenom }} {{ $circuit->guide->nom }}</p>
                                        <p class="text-xs text-gray-500">Guide professionnel</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bouton pour passer à la réservation -->
                    <div class="mt-8 flex justify-center">
                        <button @click="activeSection = 'form'" 
                            class="inline-flex items-center px-8 py-3 border border-transparent rounded-md shadow-md text-base font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Réserver maintenant
                        </button>
                    </div>
                </div>
            </div>

            <!-- Formulaire de réservation avec Alpine.js amélioré -->
            <div x-show="activeSection === 'form'" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-4">
                
                <form action="{{ route('touriste.reservations.circuits.store') }}" method="POST" x-data="{ 
                    dateDebut: '', 
                    nombrePersonnes: 1, 
                    prixUnitaire: {{ $circuit->prix }}, 
                    termsAccepted: false,
                    isSubmitting: false,
                    prixTotal: 0,
                    dateFin: '',
                    erreur: false,
                    message: '',
                    formProgress: 0,
                    formSteps: ['date', 'participants', 'confirmation'],
                    currentStep: 'date',
                    
                    // Méthodes
                    calculerPrixTotal() { 
                        this.prixTotal = (this.nombrePersonnes * this.prixUnitaire).toFixed(2);
                        return this.prixTotal;
                    }, 
                    calculerDateFin() { 
                        if (!this.dateDebut) return ''; 
                        const date = new Date(this.dateDebut); 
                        date.setDate(date.getDate() + {{ $circuit->duree - 1 }}); 
                        this.dateFin = date.toISOString().split('T')[0];
                        return this.dateFin;
                    },
                    formattedDate(dateString) {
                        if (!dateString) return '';
                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        return new Date(dateString).toLocaleDateString('fr-FR', options);
                    },
                    validateForm() {
                        if (!this.dateDebut) {
                            this.erreur = true;
                            this.message = 'La date de début est requise';
                            return false;
                        }
                        if (this.nombrePersonnes < 1) {
                            this.erreur = true;
                            this.message = 'Le nombre de personnes doit être au moins 1';
                            return false;
                        }
                        if (!this.termsAccepted) {
                            this.erreur = true;
                            this.message = 'Vous devez accepter les conditions générales';
                            return false;
                        }
                        return true;
                    },
                    submitForm() {
                        if (this.validateForm()) {
                            this.isSubmitting = true;
                            this.showConfetti = true;
                            setTimeout(() => {
                                this.$el.submit();
                            }, 1000);
                        }
                    },
                    nextStep() {
                        if (this.currentStep === 'date' && !this.dateDebut) {
                            this.erreur = true;
                            this.message = 'Veuillez sélectionner une date de début';
                            return;
                        }
                        
                        this.erreur = false;
                        
                        if (this.currentStep === 'date') {
                            this.currentStep = 'participants';
                            this.formProgress = 33;
                        } else if (this.currentStep === 'participants') {
                            this.currentStep = 'confirmation';
                            this.formProgress = 66;
                        }
                    },
                    prevStep() {
                        if (this.currentStep === 'participants') {
                            this.currentStep = 'date';
                            this.formProgress = 0;
                        } else if (this.currentStep === 'confirmation') {
                            this.currentStep = 'participants';
                            this.formProgress = 33;
                        }
                    }
                }" @submit.prevent="submitForm()" class="mt-4">
                    @csrf
                    <input type="hidden" name="circuit_id" value="{{ $circuit->id }}">

                    <!-- Barre de progression -->
                    <div class="mb-8">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2.5 rounded-full transition-all duration-500 ease-out"
                                :style="`width: ${formProgress}%`"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-gray-500">
                            <div class="text-center" :class="currentStep === 'date' ? 'text-indigo-600 font-medium' : ''">
                                <span class="w-6 h-6 inline-flex items-center justify-center rounded-full" 
                                    :class="currentStep === 'date' ? 'bg-indigo-100 text-indigo-600' : (formProgress >= 33 ? 'bg-indigo-600 text-white' : 'bg-gray-200')">1</span>
                                <p class="mt-1">Date</p>
                            </div>
                            <div class="text-center" :class="currentStep === 'participants' ? 'text-indigo-600 font-medium' : ''">
                                <span class="w-6 h-6 inline-flex items-center justify-center rounded-full"
                                    :class="currentStep === 'participants' ? 'bg-indigo-100 text-indigo-600' : (formProgress >= 66 ? 'bg-indigo-600 text-white' : 'bg-gray-200')">2</span>
                                <p class="mt-1">Participants</p>
                            </div>
                            <div class="text-center" :class="currentStep === 'confirmation' ? 'text-indigo-600 font-medium' : ''">
                                <span class="w-6 h-6 inline-flex items-center justify-center rounded-full"
                                    :class="currentStep === 'confirmation' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-200'">3</span>
                                <p class="mt-1">Confirmation</p>
                            </div>
                        </div>
                    </div>

                    <!-- Message d'erreur Alpine -->
                    <div x-show="erreur" x-transition:enter="transition ease-out duration-300" 
                        x-transition:enter-start="opacity-0 transform -translate-y-2" 
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0" 
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-md shadow-sm animate__animated animate__headShake">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700" x-text="message"></p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button @click="erreur = false" type="button" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400 transition-all duration-200">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Étape 1: Date de début -->
                    <div x-show="currentStep === 'date'" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform translate-y-4">
                        
                        <div class="bg-white rounded-xl p-5 shadow-lg border border-gray-200 transition-all duration-200 hover:shadow-xl transform hover:-translate-y-1">
                            <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-900">Choisissez vos dates</h3>
                            </div>
                            
                            <div class="mb-4">
                                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                                <input type="date" id="date_debut" name="date_debut" x-model="dateDebut" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    min="{{ date('Y-m-d') }}" @change="calculerDateFin()">
                            </div>
                            
                            <div class="mb-4" x-show="dateDebut">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin (calculée)</label>
                                <div class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-50 text-gray-700 sm:text-sm">
                                    <span x-text="formattedDate(calculerDateFin())"></span>
                                </div>
                                <input type="hidden" name="date_fin" x-model="dateFin">
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <button type="button" @click="nextStep()" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                    Continuer
                                    <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Étape 2: Nombre de participants -->
                    <div x-show="currentStep === 'participants'" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform translate-y-4">
                        
                        <div class="bg-white rounded-xl p-5 shadow-lg border border-gray-200 transition-all duration-200 hover:shadow-xl transform hover:-translate-y-1">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-900">Nombre de participants</h3>
                            </div>

                            <div class="mb-6">
                                <label for="nombre_personnes" class="block text-sm font-medium text-gray-700 mb-1">Nombre de personnes</label>
                                <div class="flex items-center">
                                    <button type="button" @click="nombrePersonnes > 1 ? nombrePersonnes-- : 1; calculerPrixTotal()" 
                                        class="p-2 rounded-l-md border border-gray-300 bg-gray-50 text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                        </svg>
                                    </button>
                                    <input type="number" id="nombre_personnes" name="nombre_personnes" x-model="nombrePersonnes" min="1" max="20"
                                        class="block w-full border-y border-gray-300 py-2 px-3 text-center focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        @change="calculerPrixTotal()">
                                    <button type="button" @click="nombrePersonnes++; calculerPrixTotal()" 
                                        class="p-2 rounded-r-md border border-gray-300 bg-gray-50 text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-6 bg-indigo-50 p-4 rounded-lg">
                                <h4 class="font-medium text-indigo-800 mb-2">Récapitulatif du prix</h4>
                                <div class="flex justify-between items-center text-sm text-indigo-700 mb-1">
                                    <span>Prix unitaire :</span>
                                    <span>{{ number_format($circuit->prix, 2) }} €</span>
                                </div>
                                <div class="flex justify-between items-center text-sm text-indigo-700 mb-1">
                                    <span>Nombre de personnes :</span>
                                    <span x-text="nombrePersonnes"></span>
                                </div>
                                <div class="border-t border-indigo-200 my-2"></div>
                                <div class="flex justify-between items-center font-medium text-indigo-900">
                                    <span>Prix total :</span>
                                    <span x-text="calculerPrixTotal() + ' €'"></span>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-between">
                                <button type="button" @click="prevStep()" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                    <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                    </svg>
                                    Retour
                                </button>
                                <button type="button" @click="nextStep()" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                    Continuer
                                    <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Étape 3: Confirmation -->
                    <div x-show="currentStep === 'confirmation'" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform translate-y-4">
                        
                        <div class="bg-white rounded-xl p-5 shadow-lg border border-gray-200 transition-all duration-200 hover:shadow-xl transform hover:-translate-y-1">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-900">Confirmation de la réservation</h3>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                <h4 class="font-medium text-gray-800 mb-3">Récapitulatif de votre réservation</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="border-l-4 border-indigo-400 pl-3 py-1">
                                        <p class="text-xs text-gray-500">Circuit</p>
                                        <p class="font-medium text-gray-800">{{ $circuit->nom }}</p>
                                    </div>
                                    
                                    <div class="border-l-4 border-indigo-400 pl-3 py-1">
                                        <p class="text-xs text-gray-500">Guide</p>
                                        <p class="font-medium text-gray-800">{{ $circuit->guide->prenom }} {{ $circuit->guide->nom }}</p>
                                    </div>
                                    
                                    <div class="border-l-4 border-indigo-400 pl-3 py-1">
                                        <p class="text-xs text-gray-500">Date de début</p>
                                        <p class="font-medium text-gray-800" x-text="formattedDate(dateDebut)"></p>
                                    </div>
                                    
                                    <div class="border-l-4 border-indigo-400 pl-3 py-1">
                                        <p class="text-xs text-gray-500">Date de fin</p>
                                        <p class="font-medium text-gray-800" x-text="formattedDate(dateFin)"></p>
                                    </div>
                                    
                                    <div class="border-l-4 border-indigo-400 pl-3 py-1">
                                        <p class="text-xs text-gray-500">Nombre de personnes</p>
                                        <p class="font-medium text-gray-800" x-text="nombrePersonnes"></p>
                                    </div>
                                    
                                    <div class="border-l-4 border-indigo-400 pl-3 py-1">
                                        <p class="text-xs text-gray-500">Prix total</p>
                                        <p class="font-medium text-indigo-600" x-text="calculerPrixTotal() + ' €'"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="terms" name="terms" type="checkbox" x-model="termsAccepted"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded transition-all duration-200">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="terms" class="font-medium text-gray-700">J'accepte les conditions générales</label>
                                        <p class="text-gray-500">En validant cette réservation, vous acceptez nos conditions de vente et notre politique d'annulation.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-between">
                                <button type="button" @click="prevStep()" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                    <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                    </svg>
                                    Retour
                                </button>
                                <button type="submit" 
                                    :disabled="isSubmitting || !termsAccepted"
                                    :class="{'opacity-50 cursor-not-allowed': isSubmitting || !termsAccepted}"
                                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-md text-base font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
                                    <span x-show="!isSubmitting">Confirmer la réservation</span>
                                    <span x-show="isSubmitting" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Traitement en cours...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Animation de confetti lorsque le formulaire est soumis -->
    <div x-show="showConfetti" 
        class="fixed inset-0 z-50 pointer-events-none" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <canvas id="confetti-canvas" class="w-full h-full"></canvas>
        <script>
            document.addEventListener('alpine:initialized', () => {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            });
        </script>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>
@endpush