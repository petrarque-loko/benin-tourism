@extends('layouts.app')

@section('content')
<div 


<div class="bg-cover bg-center bg-fixed min-h-screen py-6" 
     style="background-image: url('/images/background.jpg');">
    <div " 
         x-data="siteEditor()">
        <!-- Reste du contenu... -->
    </div>

    <div class="max-w-4xl mx-auto">
        <!-- Card avec effet d'ombre et bordure subtile -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transform transition duration-500 hover:shadow-2xl">
            <!-- En-tête avec dégradé -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 flex items-center">
                <div class="flex-shrink-0 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white">Modifier votre profil</h2>
            </div>

            <!-- Corps du formulaire avec animations -->
            <div class="p-6 md:p-8" 
                 x-data="{ 
                     showSuccess: false,
                     focusedField: null
                 }">
                <form method="POST" action="{{ route('profile.update') }}" 
                      @submit="showSuccess = true; setTimeout(() => showSuccess = false, 3000)">
                    @csrf
                    @method('PUT')

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
                                <p class="text-sm text-green-700">Profil mis à jour avec succès !</p>
                            </div>
                        </div>
                    </div>

                    <!-- Champ Nom -->
                    <div class="mb-6">
                        <label for="nom" 
                               class="block text-sm font-medium text-gray-700 mb-1"
                               :class="{ 'text-indigo-600': focusedField === 'nom' }">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Nom
                            </div>
                        </label>
                        <div class="relative">
                            <input id="nom" 
                                   type="text" 
                                   class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-200"
                                   :class="{ 'ring ring-indigo-200 ring-opacity-50 border-indigo-500': focusedField === 'nom' }"
                                   name="nom" 
                                   value="{{ old('nom', auth()->user()->nom) }}" 
                                   required 
                                   autocomplete="nom" 
                                   autofocus
                                   @focus="focusedField = 'nom'"
                                   @blur="focusedField = null">
                            @error('nom')
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Champ Prénom -->
                    <div class="mb-6">
                        <label for="prenom" 
                               class="block text-sm font-medium text-gray-700 mb-1"
                               :class="{ 'text-indigo-600': focusedField === 'prenom' }">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Prénom
                            </div>
                        </label>
                        <div class="relative">
                            <input id="prenom" 
                                   type="text" 
                                   class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-200"
                                   :class="{ 'ring ring-indigo-200 ring-opacity-50 border-indigo-500': focusedField === 'prenom' }"
                                   name="prenom" 
                                   value="{{ old('prenom', auth()->user()->prenom) }}" 
                                   required 
                                   autocomplete="prenom"
                                   @focus="focusedField = 'prenom'"
                                   @blur="focusedField = null">
                            @error('prenom')
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Champ Téléphone -->
                    <div class="mb-6">
                        <label for="telephone" 
                               class="block text-sm font-medium text-gray-700 mb-1"
                               :class="{ 'text-indigo-600': focusedField === 'telephone' }">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Téléphone
                            </div>
                        </label>
                        <div class="relative" 
                             x-data="{ phoneValue: '{{ old('telephone', auth()->user()->telephone) }}' }">
                            <input id="telephone" 
                                   type="text" 
                                   class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-200"
                                   :class="{ 'ring ring-indigo-200 ring-opacity-50 border-indigo-500': focusedField === 'telephone' }"
                                   name="telephone" 
                                   x-model="phoneValue"
                                   required 
                                   autocomplete="telephone"
                                   @focus="focusedField = 'telephone'"
                                   @blur="focusedField = null">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <span x-show="!phoneValue" class="text-gray-400" style="display: none;">+33</span>
                            </div>
                            @error('telephone')
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Champ Adresse -->
                    <div class="mb-8">
                        <label for="adresse" 
                               class="block text-sm font-medium text-gray-700 mb-1"
                               :class="{ 'text-indigo-600': focusedField === 'adresse' }">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Adresse
                            </div>
                        </label>
                        <div class="relative">
                            <textarea id="adresse" 
                                     class="form-textarea mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-200"
                                     :class="{ 'ring ring-indigo-200 ring-opacity-50 border-indigo-500': focusedField === 'adresse' }"
                                     name="adresse" 
                                     required 
                                     autocomplete="adresse"
                                     rows="3"
                                     @focus="focusedField = 'adresse'"
                                     @blur="focusedField = null">{{ old('adresse', auth()->user()->adresse) }}</textarea>
                                     @error('adresse')
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                      
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection