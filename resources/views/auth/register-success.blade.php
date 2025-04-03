@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 to-blue-100 py-12 px-4 sm:px-6 lg:px-8"

<div class="bg-cover bg-center bg-fixed min-h-screen py-6" 
     style="background-image: url('/images/background.jpg');">
    <div " 
         x-data="siteEditor()">
        <!-- Reste du contenu... -->
    </div>

    <div class="max-w-md w-full">
        <!-- Card avec animation d'apparition -->
        <div 
            x-data="{ show: false }" 
            x-init="setTimeout(() => show = true, 100)" 
            x-show="show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="bg-white rounded-xl shadow-xl overflow-hidden"
        >
            <!-- Entête avec dégradé -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <h2 class="text-white font-bold text-xl tracking-wide flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('Inscription réussie') }}
                </h2>
            </div>

            <div class="p-6">
                <!-- Message de succès avec animation -->
                <div 
                    x-data="{ showNotif: false }" 
                    x-init="showNotif = true; setTimeout(() => { showNotif = false }, 10000)"
                    x-show="showNotif"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-700"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r"
                >
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800 font-medium">
                                {{ session('message') ?? 'Votre inscription a été traitée avec succès.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Illustration dynamique selon le type de message -->
                <div class="flex justify-center my-6">
                    @if(strpos(session('message'), 'vérification') !== false)
                        <!-- Icône de vérification en attente pour guides et propriétaires -->
                        <svg class="w-32 h-32 text-yellow-500" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M50 10C27.9086 10 10 27.9086 10 50C10 72.0914 27.9086 90 50 90C72.0914 90 90 72.0914 90 50C90 27.9086 72.0914 10 50 10Z" stroke="currentColor" stroke-width="4"/>
                            <path d="M50 30V50L65 65" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @else
                        <!-- Icône d'email pour touristes -->
                        <svg class="w-32 h-32 text-indigo-500" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="15" y="25" width="70" height="50" rx="5" stroke="currentColor" stroke-width="4"/>
                            <path d="M15 35L50 55L85 35" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @endif
                </div>

                <!-- Texte de confirmation contextuel -->
                <div 
                    x-data="{}"
                    x-init=""
                    class="text-gray-600 text-center mb-8 space-y-2"
                >
                    @if(strpos(session('message'), 'vérification') !== false)
                        <p>Nous examinons actuellement vos informations et documents.</p>
                        <p>Vous recevrez une notification dès que votre compte sera validé.</p>
                    @else
                        <p>Un email d'activation a été envoyé à votre adresse email.</p>
                        <p>Veuillez vérifier votre boîte de réception et suivre les instructions pour activer votre compte.</p>
                    @endif
                </div>

                <!-- Bouton avec effet hover et focus -->
                <div class="space-y-4">
                    <div class="flex justify-center">
                        <a 
                            href="{{ route('login') }}" 
                            class="py-3 px-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full sm:w-auto text-center flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            {{ __('Se connecter') }}
                        </a>
                    </div>
                    
                    @if(strpos(session('message'), 'email') !== false)
                        <div class="text-center">
                            <button 
                                type="button"
                                x-data="{showHint: false}"
                                @click="showHint = !showHint"
                                class="text-sm text-indigo-600 hover:text-indigo-800 focus:outline-none"
                            >
                                Je n'ai pas reçu d'email d'activation
                            </button>
                            <div 
                                x-show="showHint"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                class="mt-2 text-xs text-gray-500 bg-gray-50 p-2 rounded"
                            >
                                Vérifiez votre dossier spam ou contactez notre support si vous n'avez pas reçu d'email dans les 15 minutes.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Footer avec indication contextuelle -->
            <div class="px-6 py-3 bg-gray-50 text-center">
                <p 
                    x-data="{}"
                    class="text-sm text-gray-500"
                >
                    @if(strpos(session('message'), 'vérification') !== false)
                        <span class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            Temps d'attente estimé: 24-48 heures
                        </span>
                    @else
                        <span class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1 text-indigo-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            L'activation est nécessaire pour accéder à votre compte
                        </span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection