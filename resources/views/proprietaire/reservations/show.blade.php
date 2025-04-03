@extends('layouts.proprietaire')

@section('title', 'Détails de la réservation')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Détails de la réservation</h1>
        <a href="{{ route('proprietaire.reservations.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-lg font-semibold">Réservation #{{ $reservation->id }}</h2>
                    <p class="text-sm text-gray-500">
                        Créée le {{ $reservation->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <div x-data="{ open: false }">
                    @switch($reservation->statut)
                        @case('approuvé')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Confirmée
                            </span>
                            @break
                        @case('en_attente')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                En attente
                            </span>
                            @break
                        @case('annulée')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Annulée
                            </span>
                            @break
                        @default
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $reservation->statut }}
                            </span>
                    @endswitch
                </div>
            </div>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
            <!-- Informations client -->
            <div>
                <h3 class="text-base font-semibold mb-3 pb-2 border-b border-gray-200">Informations client</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nom</p>
                        <p class="mt-1"> <a href=" {{ route('users.show', $reservation->user->id ) }} ">{{ $reservation->user->nom }} {{ $reservation->user->prenom }}</a> </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="mt-1">{{ $reservation->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Téléphone</p>
                        <p class="mt-1">{{ $reservation->user->telephone ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Détails de la réservation -->
            <div>
                <h3 class="text-base font-semibold mb-3 pb-2 border-b border-gray-200">Détails de la réservation</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Hébergement</p>
                        <p class="mt-1">{{ $reservation->chambre->hebergement->nom }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Chambre</p>
                        <a href="{{ route('proprietaire.hebergements.chambres.show', [$reservation->chambre->hebergement->id, $reservation->chambre->id]) }}">{{ $reservation->chambre->nom }}</a>                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Période</p>
                        <p class="mt-1">Du {{ $reservation->date_debut->format('d/m/Y') }} au {{ $reservation->date_fin->format('d/m/Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $reservation->date_debut->diffInDays($reservation->date_fin) }} nuits</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nombre de personnes</p>
                        <p class="mt-1">{{ $reservation->nombre_personnes ?? 'Non spécifié' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Section Paiement (commentée car probablement absente) -->
            {{-- 
            <div class="md:col-span-2">
                <h3 class="text-base font-semibold mb-3 pb-2 border-b border-gray-200">Paiement</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Montant total</p>
                        <p class="mt-1 text-lg font-bold">{{ number_format($reservation->montant_total, 2, ',', ' ') }} €</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Montant payé</p>
                        <p class="mt-1">{{ number_format($reservation->montant_paye ?? 0, 2, ',', ' ') }} €</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Solde restant</p>
                        <p class="mt-1 {{ ($reservation->montant_total - ($reservation->montant_paye ?? 0)) > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($reservation->montant_total - ($reservation->montant_paye ?? 0), 2, ',', ' ') }} €
                        </p>
                    </div>
                </div>
            </div>
            --}}
            
            <!-- Notes (affichées uniquement si elles existent) -->
            @if($reservation->notes)
            <div class="md:col-span-2">
                <h3 class="text-base font-semibold mb-3 pb-2 border-b border-gray-200">Notes</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-700">{{ $reservation->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection