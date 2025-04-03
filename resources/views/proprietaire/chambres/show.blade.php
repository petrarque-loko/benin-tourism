@extends('layouts.proprietaire')

@section('title', 'Détails de la chambre')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $chambre->nom }}</h1>
            <p class="text-sm text-gray-600">
                <a href="{{ route('proprietaire.hebergements.index') }}" class="text-blue-600 hover:text-blue-800">Hébergements</a> &gt; 
                <a href="{{ route('proprietaire.hebergements.show', $hebergement->id) }}" class="text-blue-600 hover:text-blue-800">{{ $hebergement->nom }}</a> &gt; 
                <a href="{{ route('proprietaire.hebergements.chambres.index', $hebergement->id) }}" class="text-blue-600 hover:text-blue-800">Chambres</a> &gt; 
                <span class="font-medium">{{ $chambre->nom }}</span>
            </p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('proprietaire.hebergements.chambres.edit', [$hebergement->id, $chambre->id]) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <i class="fas fa-edit mr-1"></i> Modifier
            </a>
            <form action="{{ route('proprietaire.hebergements.chambres.destroy', [$hebergement->id, $chambre->id]) }}" method="POST" class="inline" x-data="{ confirmDelete: false }">
                @csrf
                @method('DELETE')
                <button type="button" @click="confirmDelete = true" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    <i class="fas fa-trash mr-1"></i> Supprimer
                </button>
                
                <!-- Modal de confirmation -->
                <div x-show="confirmDelete" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                    <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
                        <h3 class="text-lg font-bold mb-4">Confirmation de suppression</h3>
                        <p class="mb-6">Êtes-vous sûr de vouloir supprimer cette chambre ? Cette action est irréversible.</p>
                        <div class="flex justify-end space-x-3">
                            <button type="button" @click="confirmDelete = false" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Annuler</button>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Supprimer</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Informations de la chambre -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Colonne gauche: Informations générales -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Informations générales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-700 mb-2"><span class="font-medium">Nom:</span> {{ $chambre->nom }}</p>
                        @if($chambre->numero)
                            <p class="text-gray-700 mb-2"><span class="font-medium">Numéro:</span> {{ $chambre->numero }}</p>
                        @endif
                        <p class="text-gray-700 mb-2"><span class="font-medium">Type:</span> {{ $chambre->type_chambre }}</p>
                        <p class="text-gray-700 mb-2"><span class="font-medium">Capacité:</span> {{ $chambre->capacite }} personne(s)</p>
                        <p class="text-gray-700 mb-2"><span class="font-medium">Prix:</span> {{ number_format($chambre->prix, 2) }} €</p>
                    </div>
                    <div>
                        <p class="text-gray-700 mb-2">
                            <span class="font-medium">Statut:</span> 
                            @if($chambre->est_visible)
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Visible</span>
                            @else
                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-800 rounded text-sm">Masquée</span>
                            @endif
                        </p>
                        <p class="text-gray-700 mb-2">
                            <span class="font-medium">Disponibilité:</span> 
                            @if($chambre->est_disponible)
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Disponible</span>
                            @else
                                <span class="inline-block px-2 py-1 bg-red-100 text-red-800 rounded text-sm">Indisponible</span>
                            @endif
                        </p>
                        <div class="flex space-x-2 mt-4">
                            <form action="{{ route('proprietaire.hebergements.chambres.toggle-availability', [$hebergement->id, $chambre->id]) }}" method="POST">
                                @csrf
                                @method('POST')
                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                    {{ $chambre->est_disponible ? 'Rendre indisponible' : 'Rendre disponible' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Description</h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($chambre->description)) !!}
                </div>
            </div>

            <!-- Équipements -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Équipements</h2>
                @if($chambre->equipements->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($chambre->equipements as $equipement)
                            <div class="flex items-center p-2 rounded bg-gray-50">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>{{ $equipement->nom }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic">Aucun équipement renseigné.</p>
                @endif
            </div>
        </div>

        <!-- Colonne droite: Galerie d'images -->
        <div>
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Galerie d'images</h2>
                <div x-data="{ activeImage: null, isOpen: false }" class="relative">
                    @if($chambre->medias->count() > 0)
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($chambre->medias as $media)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $media->url) }}" alt="{{ $chambre->nom }}" class="w-full h-32 object-cover rounded cursor-pointer" @click="activeImage = '{{ asset('storage/' . $media->url) }}'; isOpen = true">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-opacity flex items-center justify-center">
                                        <button type="button" @click="activeImage = '{{ asset('storage/' . $media->url) }}'; isOpen = true" class="text-white bg-black bg-opacity-50 p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-search-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Modal pour l'affichage d'image -->
                        <div x-show="isOpen" @click.away="isOpen = false" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" x-cloak>
                            <div class="relative max-w-4xl w-full p-4">
                                <button @click="isOpen = false" class="absolute top-0 right-0 -mt-10 -mr-10 bg-white rounded-full p-2 text-gray-800 hover:text-gray-600">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                                <img :src="activeImage" alt="{{ $chambre->nom }}" class="w-full h-auto max-h-screen object-contain">
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 italic">Aucune image disponible.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Réservations -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 border-b pb-2">Réservations</h2>
        @if($chambre->reservations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'arrivée</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de départ</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($chambre->reservations as $reservation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">{{ $reservation->user->nom }} {{ $reservation->user->prenom }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $reservation->date_debut ? $reservation->date_debut->format('d/m/Y') : 'Non définie' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $reservation->date_fin ? $reservation->date_fin->format('d/m/Y') : 'Non définie' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($reservation->statut == 'en_attente')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                    @elseif($reservation->statut == 'confirmée')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Confirmée
                                        </span>
                                    @elseif($reservation->statut == 'annulée')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Annulée
                                        </span>
                                    @elseif($reservation->statut == 'terminée')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Terminée
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ number_format($reservation->total, 2) }} €</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('proprietaire.reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-900">Voir détails</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 italic">Aucune réservation pour cette chambre.</p>
        @endif
    </div>

</div>
@endsection