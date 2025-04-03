<!-- resources/views/auth/passwords/reset.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8"



<div class="bg-cover bg-center bg-fixed min-h-screen py-6" 
     style="background-image: url('/images/background.jpg');">
    <div 
         x-data="siteEditor()">
        <!-- Reste du contenu... -->
    </div>

    <div class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-xl transform transition-all duration-500 hover:shadow-2xl"
         x-data="{ 
            showPassword: false,
            showConfirmPassword: false,
            passwordStrength: 0,
            formSubmitted: false,
            updatePasswordStrength() {
                const password = document.getElementById('password').value;
                let strength = 0;
                if (password.length >= 8) strength += 1;
                if (/[A-Z]/.test(password)) strength += 1;
                if (/[0-9]/.test(password)) strength += 1;
                if (/[^A-Za-z0-9]/.test(password)) strength += 1;
                this.passwordStrength = strength;
            }
         }"
         x-init="() => {
            setTimeout(() => {
                $el.classList.add('translate-y-0', 'opacity-100');
                $el.classList.remove('translate-y-4', 'opacity-0');
            }, 100);
         }"
         class="translate-y-4 opacity-0">
        
        <div class="relative">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-2xl h-32"></div>
            <div class="relative px-8 pt-10">
                <div class="text-center">
                    <div class="bg-white rounded-full h-24 w-24 flex items-center justify-center mx-auto shadow-lg border-4 border-white transform -translate-y-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h2 class="mt-2 text-3xl font-extrabold text-gray-900 tracking-tight">
                        {{ __('Réinitialiser le mot de passe') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 max-w">
                        {{ __('Créez un nouveau mot de passe sécurisé pour votre compte') }}
                    </p>
                </div>
                
                <form method="POST" action="{{ route('password.update') }}" class="mt-8 space-y-6"
                      @submit="formSubmitted = true">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <div class="rounded-md -space-y-px">
                        <!-- Email -->
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
                                <input id="email" type="email" 
                                       class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 py-3 border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 @error('email') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                       name="email" 
                                       value="{{ $email ?? old('email') }}" 
                                       required 
                                       autocomplete="email" 
                                       autofocus
                                       placeholder="exemple@domaine.com">
                                
                                @error('email')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @enderror
                            </div>
                            @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Nouveau mot de passe -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Nouveau mot de passe') }}
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password" 
                                       :type="showPassword ? 'text' : 'password'" 
                                       class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 py-3 border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 @error('password') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password" 
                                       placeholder="Minimum 8 caractères"
                                       @input="updatePasswordStrength()">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" @click="showPassword = !showPassword" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <!-- Barre de force du mot de passe -->
                            <div class="mt-2">
                                <div class="h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 rounded-full transition-all duration-300"
                                         :class="{
                                            'w-0': passwordStrength === 0,
                                            'w-1/4 bg-red-500': passwordStrength === 1,
                                            'w-2/4 bg-yellow-500': passwordStrength === 2,
                                            'w-3/4 bg-blue-500': passwordStrength === 3,
                                            'w-full bg-green-500': passwordStrength === 4
                                         }"></div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500" x-text="{
                                    0: 'Force : Aucune',
                                    1: 'Force : Faible',
                                    2: 'Force : Moyenne',
                                    3: 'Force : Bonne',
                                    4: 'Force : Excellente'
                                }[passwordStrength]"></p>
                            </div>
                        </div>
                        
                        <!-- Confirmation du mot de passe -->
                        <div class="mb-6">
                            <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Confirmer le mot de passe') }}
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password-confirm" 
                                       :type="showConfirmPassword ? 'text' : 'password'" 
                                       class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 py-3 border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 @error('password_confirmation') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password" 
                                       placeholder="Confirmez votre mot de passe">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                        <svg x-show="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                                :class="{ 'opacity-50 cursor-not-allowed': formSubmitted }"
                                :disabled="formSubmitted">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg x-show="!formSubmitted" class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                <svg x-show="formSubmitted" class="h-5 w-5 text-indigo-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            {{ __('Réinitialiser le mot de passe') }}
                        </button>
                    </div>
                </form>
                
                <!-- Lien retour connexion -->
                <div class="text-center mt-2">
                    <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                        {{ __('Retour à la connexion') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection