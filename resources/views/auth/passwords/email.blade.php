@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8"

<div class="bg-cover bg-center bg-fixed min-h-screen py-6" 
     style="background-image: url('/images/background.jpg');">
    <div " 
         x-data="siteEditor()">
        <!-- Reste du contenu... -->
    </div>

    <div class="max-w-md w-full">
        <!-- Logo et titre animés -->
        <div class="text-center mb-10" 
             x-data="{ show: false }" 
             x-init="setTimeout(() => show = true, 100)">
            <div class="flex justify-center">
                <div class="h-20 w-20 rounded-full bg-indigo-600 flex items-center justify-center shadow-lg transform transition-all duration-700"
                     :class="{'scale-100 rotate-0 opacity-100': show, 'scale-50 -rotate-90 opacity-0': !show}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 tracking-tight transition-all duration-700"
                :class="{'transform translate-y-0 opacity-100': show, 'transform translate-y-4 opacity-0': !show}">
                {{ __('Réinitialisation du mot de passe') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 transition-all duration-700 delay-100"
               :class="{'transform translate-y-0 opacity-100': show, 'transform translate-y-4 opacity-0': !show}">
                Entrez votre adresse e-mail pour recevoir un lien de réinitialisation
            </p>
        </div>

        <!-- Carte avec effet de profondeur -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden transform transition-all duration-700 hover:shadow-2xl"
             x-data="{ showCard: false }"
             x-init="setTimeout(() => showCard = true, 300)"
             :class="{'translate-y-0 opacity-100': showCard, 'translate-y-8 opacity-0': !showCard}">
            
            <!-- Barre supérieure décorative -->
            <div class="h-2 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
            
            <!-- En-tête de la carte -->
            <div class="bg-indigo-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Réinitialisation du mot de passe') }}</h3>
                </div>
            </div>

            <!-- Corps de la carte -->
            <div class="px-6 py-6">
                @if (session('status'))
                    <div class="rounded-md bg-green-50 p-4 mb-6 border border-green-200 transform transition-all duration-500 animate-pulse" 
                         x-data="{ show: true }" 
                         x-show="show" 
                         x-init="setTimeout(() => show = false, 5000)">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button @click="show = false" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <span class="sr-only">Fermer</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" 
                      x-data="{ 
                          email: '', 
                          isValid: false,
                          isSubmitting: false,
                          validate() {
                              const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                              this.isValid = emailRegex.test(this.email);
                          }
                      }"
                      @submit="isSubmitting = true">
                    @csrf

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Adresse e-mail') }}
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" 
                                   type="email" 
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 py-3 sm:text-sm border-gray-300 rounded-lg @error('email') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   placeholder="exemple@domaine.com"
                                   autofocus
                                   x-model="email"
                                   @input="validate()">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center" x-show="email.length > 0">
                                <svg x-show="isValid" class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <svg x-show="!isValid" class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        @error('email')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Nous vous enverrons un lien pour réinitialiser votre mot de passe</p>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-300"
                                :class="{'opacity-75 cursor-not-allowed': isSubmitting || !isValid, 'hover:bg-indigo-700': isValid && !isSubmitting}"
                                :disabled="isSubmitting || !isValid">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400 transition-colors duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span x-show="!isSubmitting">{{ __('Envoyer le lien de réinitialisation') }}</span>
                            <span x-show="isSubmitting" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Envoi en cours...
                            </span>
                        </button>
                    </div>
                </form>
                
                <!-- Lien de retour -->
                <div class="text-center mt-6">
                    
                    <a href="{{ route('login') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-300 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('Retour à la connexion') }}
                    </a>
                </div>
            </div>
            
            <!-- Pied de page de la carte -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="text-xs text-gray-500 text-center">
                    {{ __('Si vous ne recevez pas d\'e-mail, vérifiez votre dossier spam ou') }}
                    <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-500 transition-colors duration-300">
                        {{ __('demandez un nouveau lien') }}
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Message d'info -->
        <div class="mt-8 text-center text-xs text-gray-500"
             x-data="{ showTip: false }"
             x-init="setTimeout(() => showTip = true, 800)">
            <div class="bg-blue-50 rounded-lg border border-blue-100 p-4 transition-all duration-700"
                 :class="{'transform translate-y-0 opacity-100': showTip, 'transform translate-y-4 opacity-0': !showTip}">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            {{ __('Besoin d\'aide ? Contactez notre support technique à') }}
                            <a href="mailto:support@exemple.com" class="font-medium underline">support@exemple.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script supplémentaire si nécessaire
    document.addEventListener('alpine:init', () => {
        // Initialisation personnalisée pour Alpine.js si nécessaire
    });
</script>
@endpush