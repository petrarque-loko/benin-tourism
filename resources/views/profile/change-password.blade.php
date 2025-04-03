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



    <div class="max-w-4xl mx-auto">
        <!-- Card avec effet d'ombre et bordure subtile -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transform transition duration-500 hover:shadow-2xl">
            <!-- En-tête avec dégradé -->
            <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4 flex items-center">
                <div class="flex-shrink-0 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white">Changer le mot de passe</h2>
            </div>

            <!-- Corps du formulaire avec animations -->
            <div class="p-6 md:p-8" 
                 x-data="{ 
                     showSuccess: false,
                     focusedField: null,
                     passwordVisible: false,
                     newPasswordVisible: false,
                     confirmPasswordVisible: false,
                     passwordStrength: 0,
                     passwordStrengthText: '',
                     passwordValue: '',
                     passwordConfirmValue: '',
                     passwordsMatch: true,
                     
                     checkPasswordStrength() {
                         let strength = 0;
                         if (this.passwordValue.length >= 8) strength += 1;
                         if (this.passwordValue.match(/[A-Z]/)) strength += 1;
                         if (this.passwordValue.match(/[0-9]/)) strength += 1;
                         if (this.passwordValue.match(/[^A-Za-z0-9]/)) strength += 1;
                         
                         this.passwordStrength = strength;
                         
                         switch(strength) {
                             case 0: this.passwordStrengthText = 'Très faible'; break;
                             case 1: this.passwordStrengthText = 'Faible'; break;
                             case 2: this.passwordStrengthText = 'Moyen'; break;
                             case 3: this.passwordStrengthText = 'Fort'; break;
                             case 4: this.passwordStrengthText = 'Très fort'; break;
                         }
                     },
                     
                     checkPasswordMatch() {
                         this.passwordsMatch = this.passwordValue === this.passwordConfirmValue || this.passwordConfirmValue === '';
                     }
                 }">
                <!-- Message d'erreur de session -->
                @if (session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md animate-pulse">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Notification de succès avec animation -->
                <div 
                    x-show="showSuccess" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-90"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-90"
                    class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md"
                    style="display: none;"
                >
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">Mot de passe changé avec succès !</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.password.update') }}" 
                      @submit="showSuccess = true; setTimeout(() => showSuccess = false, 3000)">
                    @csrf
                    @method('PUT')

                    <!-- Champ Mot de passe actuel -->
                    <div class="mb-6">
                        <label for="current_password" 
                               class="block text-sm font-medium text-gray-700 mb-1"
                               :class="{ 'text-purple-600': focusedField === 'current_password' }">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Mot de passe actuel
                            </div>
                        </label>
                        <div class="relative">
                            <input id="current_password" 
                                   :type="passwordVisible ? 'text' : 'password'" 
                                   class="form-input pl-3 pr-10 py-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200"
                                   :class="{ 'ring ring-purple-200 ring-opacity-50 border-purple-500': focusedField === 'current_password' }"
                                   name="current_password" 
                                   required 
                                   autocomplete="current-password"
                                   @focus="focusedField = 'current_password'"
                                   @blur="focusedField = null">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <button type="button" @click="passwordVisible = !passwordVisible" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!passwordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="passwordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="absolute inset-y-0 right-0 flex items-center pr-10 pointer-events-none">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Champ Nouveau mot de passe -->
                    <div class="mb-6">
                        <label for="password" 
                               class="block text-sm font-medium text-gray-700 mb-1"
                               :class="{ 'text-purple-600': focusedField === 'password' }">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Nouveau mot de passe
                            </div>
                        </label>
                        <div class="relative">
                            <input id="password" 
                                   :type="newPasswordVisible ? 'text' : 'password'" 
                                   class="form-input pl-3 pr-10 py-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200"
                                   :class="{ 'ring ring-purple-200 ring-opacity-50 border-purple-500': focusedField === 'password' }"
                                   name="password" 
                                   required 
                                   autocomplete="new-password"
                                   x-model="passwordValue"
                                   @input="checkPasswordStrength()"
                                   @focus="focusedField = 'password'"
                                   @blur="focusedField = null">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <button type="button" @click="newPasswordVisible = !newPasswordVisible" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!newPasswordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>

                                    <svg x-show="newPasswordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <div class="absolute inset-y-0 right-0 flex items-center pr-10 pointer-events-none">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Indicateur de force du mot de passe -->
                        <div class="mt-2">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs text-gray-500">Force du mot de passe: <span x-text="passwordStrengthText" :class="{
                                    'text-red-500': passwordStrength <= 1,
                                    'text-yellow-500': passwordStrength === 2,
                                    'text-green-500': passwordStrength >= 3
                                }"></span></span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full transition-all duration-300"
                                     :class="{
                                         'bg-red-500': passwordStrength <= 1,
                                         'bg-yellow-500': passwordStrength === 2,
                                         'bg-green-500': passwordStrength >= 3
                                     }"
                                     :style="'width: ' + (passwordStrength * 25) + '%'"></div>
                            </div>
                            <ul class="text-xs text-gray-500 mt-2 space-y-1 pl-5 list-disc">
                                <li>Au moins 8 caractères</li>
                                <li>Au moins une lettre majuscule</li>
                                <li>Au moins un chiffre</li>
                                <li>Au moins un caractère spécial</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Champ Confirmer le mot de passe -->
                    <div class="mb-8">
                        <label for="password_confirmation" 
                               class="block text-sm font-medium text-gray-700 mb-1"
                               :class="{ 'text-purple-600': focusedField === 'password_confirmation' }">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Confirmer le mot de passe
                            </div>
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" 
                                   :type="confirmPasswordVisible ? 'text' : 'password'" 
                                   class="form-input pl-3 pr-10 py-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200"
                                   :class="{ 
                                       'ring ring-purple-200 ring-opacity-50 border-purple-500': focusedField === 'password_confirmation',
                                       'border-red-300 focus:border-red-500 focus:ring-red-200': !passwordsMatch && passwordConfirmValue !== ''
                                   }"
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   x-model="passwordConfirmValue"
                                   @input="checkPasswordMatch()"
                                   @focus="focusedField = 'password_confirmation'"
                                   @blur="focusedField = null">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <button type="button" @click="confirmPasswordVisible = !confirmPasswordVisible" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!confirmPasswordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="confirmPasswordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <div x-show="!passwordsMatch && passwordConfirmValue !== ''" class="absolute inset-y-0 right-0 flex items-center pr-10 pointer-events-none">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <p x-show="!passwordsMatch && passwordConfirmValue !== ''" class="mt-2 text-sm text-red-600">Les mots de passe ne correspondent pas.</p>
                    </div>

                    <!-- Bouton de soumission avec effet de hover -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transform transition duration-200 hover:scale-105"
                                :class="{ 'opacity-50 cursor-not-allowed': !passwordsMatch && passwordConfirmValue !== '' }"
                                :disabled="!passwordsMatch && passwordConfirmValue !== ''">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Mettre à jour le mot de passe
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Pied de page avec conseils de sécurité -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                <h3 class="text-sm font-medium text-gray-700">Conseils de sécurité:</h3>
                <ul class="mt-2 text-xs text-gray-500 space-y-1">
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Utilisez un mot de passe unique pour chaque site web
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Évitez d'utiliser des informations personnelles
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Changez régulièrement vos mots de passe
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection