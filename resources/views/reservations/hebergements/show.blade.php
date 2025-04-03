@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50  py-10" 
    x-data="{
        reservation: {
            id: {{ $reservation->id }},
            statut: '{{ $reservation->statut }}',
            statut_paiement: '{{ $reservation->statut_paiement }}',
            reference_paiement: '{{ $reservation->reference_paiement }}',
            nombre_personnes: {{ $reservation->nombre_personnes }},
            raison_annulation: '{{ $reservation->raison_annulation ?? '' }}',
            created_at: '{{ $reservation->created_at->format('d/m/Y H:i') }}'
        },
        chambre: {
            id: {{ $reservation->reservable->id }},
            nom: '{{ $reservation->reservable->nom }}',
            type_chambre: '{{ $reservation->reservable->type_chambre }}',
            prix: {{ $reservation->reservable->prix }}
        },
        hebergement: {
            id: {{ $reservation->reservable->hebergement->id }},
            nom: '{{ $reservation->reservable->hebergement->nom }}',
            ville: '{{ $reservation->reservable->hebergement->ville }}'
        },
        dateDebut: '{{ $reservation->date_debut->format('Y-m-d') }}',
        dateFin: '{{ $reservation->date_fin->format('Y-m-d') }}',
        dateDebutFormatted: '{{ $reservation->date_debut->format('d/m/Y') }}',
        dateFinFormatted: '{{ $reservation->date_fin->format('d/m/Y') }}',
        statusLabels: {
            'en_attente': 'En attente',
            'confirmee': 'Confirmée',
            'annulee': 'Annulée',
            'terminee': 'Terminée'
        },
        paymentLabels: {
            'en_attente': 'En attente',
            'paye': 'Payé',
            'rembourse': 'Remboursé'
        },
        get statutClass() {
            return {
                'en_attente': 'bg-amber-100 text-amber-800 border-amber-200',
                'confirmee': 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'annulee': 'bg-rose-100 text-rose-800 border-rose-200',
                'terminee': 'bg-slate-100 text-slate-800 border-slate-200'
            }[this.reservation.statut] || 'bg-slate-100 text-slate-800 border-slate-200';
        },
        get statutPaiementClass() {
            return {
                'en_attente': 'bg-amber-100 text-amber-800 border-amber-200',
                'paye': 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'rembourse': 'bg-sky-100 text-sky-800 border-sky-200'
            }[this.reservation.statut_paiement] || 'bg-slate-100 text-slate-800 border-slate-200';
        },
        get nombreJours() {
            const debut = new Date(this.dateDebut);
            const fin = new Date(this.dateFin);
            const diffTime = Math.abs(fin - debut);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return diffDays || 1;
        },
        get prixTotal() {
            return this.nombreJours * this.chambre.prix;
        },
        get peutModifier() {
            return this.reservation.statut !== 'annulee' && this.reservation.statut !== 'terminee';
        },
        get peutAnnuler() {
            return this.reservation.statut !== 'annulee' && this.reservation.statut !== 'terminee';
        }
    }">
    
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Notification de succès -->
        @if(session('success'))
            <div class="mb-6 transform transition-all duration-300 animate-fade-in-down" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <div class="rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 p-0.5 shadow-lg">
                    <div class="rounded-md bg-white px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-800">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Carte de détails de la réservation -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-2xl">
            <!-- En-tête avec gradient -->
            <div class="bg-gradient-to-r from-green-600 to-blue-500 text-white px-8 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold tracking-tight">Détails de la réservation</h1>
                        <p class="mt-1 text-blue-100 text-sm">Créée le <span x-text="reservation.created_at"></span></p>
                    </div>
                    <div class="flex items-center space-x-3">
                        
                        <template x-if="reservation.statut !== 'annulee'">
                        <div class="px-4 py-1.5 rounded-full border text-sm font-semibold shadow-sm bg-yellow-500 text-white" 
                            x-bind:class="statutPaiementClass" 
                            x-text="paymentLabels[reservation.statut_paiement]">
                        </div>

                        </template>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Informations sur l'hébergement et la chambre -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3">
                            <div class="bg-indigo-100 rounded-full p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Hébergement</h2>
                        </div>
                        
                        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-6 border border-indigo-100">
                            <div class="mb-4 pb-4 border-b border-indigo-100">
                                <h3 class="text-xl font-bold text-indigo-700" x-text="hebergement.nom"></h3>
                                <div class="flex items-center mt-2 text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span x-text="hebergement.ville"></span>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <div class="font-medium text-gray-700">Chambre</div>
                                    <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg" x-text="chambre.type_chambre"></span>
                                </div>
                                <div class="text-lg font-bold text-gray-800" x-text="chambre.nom"></div>
                                
                                <div class="mt-4 pt-4 border-t border-indigo-100 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-700 font-medium">Prix par nuit</span>
                                    </div>
                                    <span class="text-lg font-bold text-indigo-600" x-text="chambre.prix + ' €'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails du séjour -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3">
                            <div class="bg-indigo-100 rounded-full p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Détails du séjour</h2>
                        </div>
                        
                        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-6 border border-indigo-100">
                            <div class="flex items-center justify-between mb-6 pb-4 border-b border-indigo-100">
                                <div class="flex items-center space-x-3">
                                    <div class="flex flex-col items-center justify-center w-12 h-12 bg-green-600 text-white rounded-lg">
                                        <span class="text-xs font-medium" x-text="dateDebutFormatted.split('/')[1]"></span>
                                        <span class="text-lg font-bold leading-none" x-text="dateDebutFormatted.split('/')[0]"></span>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Arrivée</div>
                                        <div class="font-bold text-gray-800" x-text="dateDebutFormatted"></div>
                                    </div>
                                </div>
                                
                                <div class="text-center px-4">
                                    <div class="text-xs font-semibold text-indigo-600 mb-1">Durée</div>
                                    <div class="text-xl font-extrabold text-indigo-700" x-text="nombreJours"></div>
                                    <div class="text-xs text-gray-500" x-text="nombreJours > 1 ? 'nuits' : 'nuit'"></div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <div class="flex flex-col items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-lg">
                                        <span class="text-xs font-medium" x-text="dateFinFormatted.split('/')[1]"></span>
                                        <span class="text-lg font-bold leading-none" x-text="dateFinFormatted.split('/')[0]"></span>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Départ</div>
                                        <div class="font-bold text-gray-800" x-text="dateFinFormatted"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <div class="text-sm text-gray-500">Référence</div>
                                    <div class="font-mono font-medium text-gray-800" x-text="reservation.reference_paiement"></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Voyageurs</div>
                                    <div class="font-medium text-gray-800" x-text="reservation.nombre_personnes + ' personne' + (reservation.nombre_personnes > 1 ? 's' : '')"></div>
                                </div>
                            </div>
                            
                            <!-- Récapitulatif du prix -->
                            <div class="bg-white rounded-xl p-4 border border-indigo-100 shadow-sm">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-gray-600">Prix par nuit</span>
                                    <span class="font-medium" x-text="chambre.prix + ' €'"></span>
                                </div>
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-gray-600">Nombre de nuits</span>
                                    <span class="font-medium" x-text="nombreJours"></span>
                                </div>
                                <div class="pt-3 mt-3 border-t border-gray-200 flex justify-between items-center">
                                    <span class="font-semibold text-gray-800">Prix total</span>
                                    <span class="text-xl font-bold text-indigo-600" x-text="prixTotal + ' €'"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Raison d'annulation si la réservation est annulée -->
                        <div x-show="reservation.statut === 'annulee'" class="bg-rose-50 rounded-xl p-6 border border-rose-200">
                            <div class="flex items-center space-x-3 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <h3 class="font-semibold text-rose-700">Raison d'annulation</h3>
                            </div>
                            <p class="text-rose-700 pl-8" x-text="reservation.raison_annulation"></p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="mt-8 flex flex-wrap justify-end gap-4">
                    <a href="{{ route('touriste.reservations.hebergements.index') }}" class="inline-flex items-center px-5 py-2.5  bg-yellow-600  text-white rounded-lg border border-black-300 shadow-sm hover:bg-stone-00 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Retour à la liste
                    </a>
                    
                    <template x-if="peutModifier">
                        <a href="{{ route('touriste.reservations.hebergements.edit', $reservation->id) }}" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Modifier
                        </a>
                    </template>
                    
                    <template x-if="peutAnnuler">
                        <a href="{{ route('touriste.reservations.hebergements.confirm-cancel', $reservation->id) }}" class="inline-flex items-center px-5 py-2.5 bg-rose-900 text-white rounded-lg shadow-md hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Annuler la réservation
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection