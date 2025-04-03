@extends('layouts.admin')

@section('title', 'Détails de la Réservation')

@section('content')
<div class="container-fluid px-4 py-6" 
     x-data="{ showCancelModal: false }"
     class="bg-cover bg-center bg-fixed py-6" 




    <div class="flex flex-wrap items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Réservation {{ $reservation->id }}</h1>
        <a href="{{ route('admin.sites.reservations') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour à la liste
        </a>
    </div>

    @if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 5000)" 
        x-show="show" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="relative px-4 py-3 mb-6 text-green-700 bg-green-100 border border-green-400 rounded-lg shadow-sm"
        role="alert"
    >
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button 
            @click="show = false" 
            class="absolute top-0 right-0 p-2 text-green-600 hover:text-green-800"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Informations de réservation -->
        <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                <div class="px-4 bg-blue-800 py-3 border-b border-gray-200 ">
                    <h6 class="text-lg text-white font-semibold ">Informations de la réservation</h6>
                </div>

            <div class="p-4">
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="font-semibold text-gray-700">Statut</div>
                    <div class="col-span-2">
                        @switch($reservation->statut)
                            @case('en_attente')
                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">En attente</span>
                                @break
                            @case('confirmé')
                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Confirmé</span>
                                @break
                            @case('en_cours')
                                <span class="px-2 py-1 text-xs font-semibold text-indigo-800 bg-indigo-100 rounded-full">En cours</span>
                                @break
                            @case('terminé')
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Terminé</span>
                                @break
                            @case('annulé')
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Annulé</span>
                                @break
                            @default
                                <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">{{ $reservation->statut }}</span>
                        @endswitch
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="font-semibold text-gray-700">Dates</div>
                    <div class="col-span-2">
                        <div class="flex flex-wrap items-center text-gray-800">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $reservation->date_debut->format('d/m/Y') }}
                            </span>
                            <span class="mx-2 text-gray-500">→</span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $reservation->date_fin->format('d/m/Y') }}
                            </span>
                        </div>
                        <span class="inline-block px-2 py-1 mt-2 text-xs text-gray-600 bg-gray-100 rounded-full">
                            {{ $reservation->date_debut->diffInDays($reservation->date_fin) + 1 }} jour(s)
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="font-semibold text-gray-700">Date de création</div>
                    <div class="col-span-2 text-gray-800">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $reservation->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="font-semibold text-gray-700">Dernière modification</div>
                    <div class="col-span-2 text-gray-800">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ $reservation->updated_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
                @if($reservation->statut === 'annulé' && $reservation->raison_annulation)
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="font-semibold text-gray-700">Raison d'annulation</div>
                        <div class="col-span-2">
                            <div class="p-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg">
                                {{ $reservation->raison_annulation }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Site touristique -->
        <div class="overflow-hidden bg-white rounded-lg shadow-sm">
            <div class="px-4 bg-blue-800 py-3 border-b border-gray-200">
                <h6 class="text-lg text-white font-semibold text-blue-600">Site touristique</h6>
            </div>
            <div class="p-4">
                @if($reservation->reservable)
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="font-semibold text-gray-700">Nom du site</div>
                        <div class="col-span-2">
                            <a href="{{ route('admin.sites.show', $reservation->reservable->id) }}" 
                               class="text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                {{ $reservation->reservable->nom }}
                            </a>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="font-semibold text-gray-700">Catégorie</div>
                        <div class="col-span-2 text-gray-800">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $reservation->reservable->categorie->nom ?? 'Non catégorisé' }}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="font-semibold text-gray-700">Localisation</div>
                        <div class="col-span-2">
                            <div class="flex items-center text-gray-800">
                                <svg class="w-4 h-4 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $reservation->reservable->localisation }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-4 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Le site touristique associé à cette réservation a été supprimé.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mt-6 lg:grid-cols-2">
        <!-- Informations du client -->
        <div class="overflow-hidden bg-white rounded-lg shadow-sm">
            <div class="px-4 bg-blue-800 py-3 border-b border-gray-200">
                <h6 class="text-lg text-white font-semibold text-blue-600">Informations du client</h6>
            </div>
            <div class="p-4">
                @if($reservation->user)
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="font-semibold text-gray-700">Nom</div>
                        <div class="col-span-2 text-gray-800"> <a href=" {{ route('users.show', $reservation->user->id ) }} ">{{ $reservation->user->nom }} {{ $reservation->user->prenom }}</a> </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="font-semibold text-gray-700">Email</div>
                        <div class="col-span-2">
                            <a href="mailto:{{ $reservation->user->email }}" 
                               class="flex items-center text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $reservation->user->email }}
                            </a>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="font-semibold text-gray-700">Téléphone</div>
                        <div class="col-span-2">
                            @if($reservation->user->phone)
                                <a href="tel:{{ $reservation->user->phone }}"
                                   class="flex items-center text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $reservation->user->phone }}
                                </a>
                            @else
                                <span class="text-gray-500">Non renseigné</span>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="font-semibold text-gray-700">Membre depuis</div>
                        <div class="col-span-2 text-gray-800">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $reservation->user->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="p-4 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            L'utilisateur associé à cette réservation a été supprimé.
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Informations du guide -->
        <div class="overflow-hidden bg-white rounded-lg shadow-sm">
            <div class="px-4 bg-blue-800 py-3 border-b border-gray-200">
                <h6 class="text-lg text-white font-semibold text-blue-600">Informations du guide</h6>
            </div>
            <div class="p-4">
                @if($reservation->guide)
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="font-semibold text-gray-700">Nom</div>
                        <div class="col-span-2 text-gray-800"><a href=" {{ route('users.show', $reservation->guide->id ) }} ">{{ $reservation->guide->nom }} {{ $reservation->guide->prenom }}</a></div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="font-semibold text-gray-700">Email</div>
                        <div class="col-span-2">
                            <a href="mailto:{{ $reservation->guide->email }}" 
                               class="flex items-center text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $reservation->guide->email }}
                            </a>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="font-semibold text-gray-700">Téléphone</div>
                        <div class="col-span-2">
                            @if($reservation->guide->phone)
                                <a href="tel:{{ $reservation->guide->phone }}"
                                   class="flex items-center text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $reservation->guide->phone }}
                                </a>
                            @else
                                <span class="text-gray-500">Non renseigné</span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="p-4 text-sm text-blue-700 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Aucun guide n'a été assigné à cette réservation.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-6 overflow-hidden bg-white rounded-lg shadow-sm">
        <div class="px-4 bg-blue-800 py-3 border-b border-gray-200">
            <h6 class="text-lg text-white font-semibold text-blue-600">Actions</h6>
        </div>
        <div class="p-4">
            <div class="flex flex-col items-start justify-between md:flex-row md:items-center">
                <div class="mb-4 md:mb-0">
                    @if($reservation->statut != 'annulé')
                    <button 
                            @click="$dispatch('open-cancel-modal')" 
                            type="button" 
                            class="flex items-center px-4 py-2 font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                            Annuler la réservation
                        </button>
                    @endif
                </div>
                <div>
                    <a href="{{ route('admin.sites.reservations') }}" class="flex items-center px-4 py-2 font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'annulation -->
@if($reservation->statut != 'annulé')
<div 
    x-data="{ showCancelModal: false }"
    @open-cancel-modal.window="showCancelModal = true"
    x-show="showCancelModal" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50"
>
    <div 
        x-show="showCancelModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        @click.away="showCancelModal = false" 
        class="relative w-full max-w-md p-6 mx-auto bg-white rounded-lg shadow-xl"
    >
        <div class="absolute top-0 right-0 pt-4 pr-4">
            <button @click="showCancelModal= false" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mb-4 text-center">
            <h3 class="text-lg font-medium text-gray-900" id="cancelModalLabel">
                Annuler la réservation #{{ $reservation->id }}
            </h3>
        </div>
        <form action="{{ route('admin.sites.reservations.cancel', $reservation) }}" method="POST">
            @csrf
            <div class="mb-4">
                <div class="p-4 mb-4 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span>Attention : Cette action est irréversible. Le client et le guide (si assigné) seront notifiés de l'annulation.</span>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="raison_annulation" class="block mb-2 text-sm font-medium text-gray-700">
                        Raison de l'annulation
                    </label>
                    <textarea 
                        id="raison_annulation" 
                        name="raison_annulation" 
                        rows="3" 
                        class="block w-full px-3 py-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Veuillez indiquer la raison de l'annulation..." 
                        required
                    ></textarea>
                </div>
            </div>
            <div class="flex justify-end mt-5 space-x-3">
                <button 
                    type="button" 
                    @click="showCancelModal = false" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Annuler
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                    Confirmer l'annulation
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
