@extends('layouts.app')

@section('title', 'Annuler ma réservation')

@section('content')
<div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Annuler ma réservation</h1>
        <a href="{{ route('touriste.reservations.circuits.show', $reservation->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Attention</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>Vous êtes sur le point d'annuler votre réservation. Cette action est irréversible.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900">Détails de la réservation</h2>
                <div class="mt-4 border rounded-md p-4 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Circuit</p>
                            <p class="mt-1 text-md font-semibold text-gray-900">{{ $reservation->reservable->nom }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Guide</p>
                            <p class="mt-1 text-md text-gray-900">{{ $reservation->guide->nom }} {{ $reservation->guide->prenom }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date de début</p>
                            <p class="mt-1 text-md text-gray-900">{{ $reservation->date_debut->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date de fin</p>
                            <p class="mt-1 text-md text-gray-900">{{ $reservation->date_fin->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nombre de personnes</p>
                            <p class="mt-1 text-md text-gray-900">{{ $reservation->nombre_personnes }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Statut</p>
                            <p class="mt-1 text-md text-gray-900">
                                @if($reservation->statut == 'en_attente')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>
                                @elseif($reservation->statut == 'approuvé')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Approuvée
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('touriste.reservations.circuits.cancel', $reservation->id) }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label for="raison_annulation" class="block text-sm font-medium text-gray-700">Raison de l'annulation</label>
                        <div class="mt-1">
                            <textarea id="raison_annulation" name="raison_annulation" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Veuillez indiquer la raison pour laquelle vous souhaitez annuler cette réservation.</p>
                        @error('raison_annulation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 bg-amber-50 border-l-4 border-amber-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-amber-800">Politique d'annulation</h3>
                            <div class="mt-2 text-sm text-amber-700">
                                <p>Selon nos conditions générales :</p>
                                <ul class="list-disc pl-5 mt-1 space-y-1">
                                    <li>Annulation plus de 7 jours avant la date : remboursement intégral</li>
                                    <li>Annulation entre 3 et 7 jours avant la date : remboursement de 50%</li>
                                    <li>Annulation moins de 3 jours avant la date : aucun remboursement</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('touriste.reservations.circuits.show', $reservation->id) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                        Retour
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Confirmer l'annulation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection