@extends('layouts.app')

@section('title', 'Modifier ma réservation')

@section('content')
<div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Modifier ma réservation</h1>
        <a href="{{ route('touriste.reservations.circuits.show', $reservation->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour
        </a>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
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

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900">Détails du circuit</h2>
                <div class="mt-4 border rounded-md p-4 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nom du circuit</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $reservation->reservable->nom }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Durée</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $reservation->reservable->duree }} jour(s)</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Prix</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($reservation->reservable->prix, 2) }} €</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Difficulté</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $reservation->reservable->difficulte }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-500">Guide</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $reservation->guide->nom }} {{ $reservation->guide->prenom }}</p>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-amber-50 border-l-4 border-amber-400 rounded-md">
                    <p class="text-sm text-amber-800">
                        <strong>Note :</strong> Vous ne pouvez modifier cette réservation que si elle est encore en attente de confirmation.
                        Une fois approuvée, vous pourrez uniquement l'annuler.
                    </p>
                </div>
            </div>

            <form action="{{ route('touriste.reservations.circuits.update', $reservation->id) }}" method="POST" x-data="{ 
                dateDebut: '{{ $reservation->date_debut->format('Y-m-d') }}', 
                nombrePersonnes: {{ $reservation->nombre_personnes }},
                prixUnitaire: {{ $reservation->reservable->prix }},
                calculerPrixTotal() {
                    return (this.nombrePersonnes * this.prixUnitaire).toFixed(2);
                },
                calculerDateFin() {
                    if (!this.dateDebut) return '';
                    const date = new Date(this.dateDebut);
                    date.setDate(date.getDate() + {{ $reservation->reservable->duree - 1 }});
                    return date.toISOString().split('T')[0];
                }
            }">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <div class="mt-1">
                            <input type="date" name="date_debut" id="date_debut" required x-model="dateDebut" min="{{ date('Y-m-d') }}" 
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('date_debut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="dateDebut">
                        <label class="block text-sm font-medium text-gray-700">Date de fin (calculée automatiquement)</label>
                        <div class="mt-1">
                            <input type="text" disabled x-bind:value="calculerDateFin()" 
                                class="bg-gray-100 shadow-sm block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Calculée en fonction de la durée du circuit ({{ $reservation->reservable->duree }} jour(s))</p>
                    </div>

                    <div>
                        <label for="nombre_personnes" class="block text-sm font-medium text-gray-700">Nombre de personnes</label>
                        <div class="mt-1">
                            <input type="number" name="nombre_personnes" id="nombre_personnes" min="1" required x-model="nombrePersonnes"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('nombre_personnes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="nombrePersonnes > 0">
                        <label class="block text-sm font-medium text-gray-700">Prix total estimé</label>
                        <div class="mt-1">
                            <p class="text-lg font-bold text-indigo-600" x-text="calculerPrixTotal() + ' €'"></p>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Calculé en fonction du nombre de personnes ({{ $reservation->reservable->prix }} € par personne)</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('touriste.reservations.circuits.show', $reservation->id) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection