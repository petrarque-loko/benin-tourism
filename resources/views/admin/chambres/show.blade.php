@extends('layouts.admin')

@section('title', 'Détails Chambre')

@section('content')
<div class="bg-gray-100">
    <div x-data="{ 
        activeTab: 'details',
        isModalOpen: false,
        currentImage: '',
        images: [
            @foreach($chambre->medias as $media)
                '{{ asset($media->url) }}',
            @endforeach
        ],
        currentImageIndex: 0,
        nextImage() {
            this.currentImageIndex = (this.currentImageIndex + 1) % this.images.length;
            this.currentImage = this.images[this.currentImageIndex];
        },
        prevImage() {
            this.currentImageIndex = (this.currentImageIndex - 1 + this.images.length) % this.images.length;
            this.currentImage = this.images[this.currentImageIndex];
        },
        openModal(index) {
            this.currentImageIndex = index;
            this.currentImage = this.images[index];
            this.isModalOpen = true;
        }
    }">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $chambre->nom }}</h1>
                    <p class="text-gray-600">{{ $chambre->hebergement->nom }} - {{ $chambre->hebergement->typeHebergement->nom }}</p>
                </div>
                <div class="flex space-x-3">
                    <form action="{{ route('admin.chambres.toggle-visibility', $chambre->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-md {{ $chambre->est_visible ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white">
                            <span x-text="{{ $chambre->est_visible ? '\'Visible\'' : '\'Masquée\'' }}"></span>
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.chambres.toggle-availability', $chambre->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-md {{ $chambre->est_disponible ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-600 hover:bg-gray-700' }} text-white">
                            <span x-text="{{ $chambre->est_disponible ? '\'Disponible\'' : '\'Indisponible\'' }}"></span>
                        </button>
                    </form>
                    
                    {{-- Commenté car 'edit' n'est pas dans le controller fourni, mais la route existe via resource --}}
                    {{-- <a href="{{ route('admin.chambres.edit', $chambre->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">Modifier</a> --}}
                </div>
            </div>
        </header>

        @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </button>
            </div>
        </div>
        @endif

        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Tabs -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'details'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'details', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'details' }" class="py-4 px-1 border-b-2 font-medium text-sm">
                        Détails
                    </button>
                    <button @click="activeTab = 'photos'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'photos', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'photos' }" class="py-4 px-1 border-b-2 font-medium text-sm">
                        Photos
                    </button>
                    <button @click="activeTab = 'equipements'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'equipements', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'equipements' }" class="py-4 px-1 border-b-2 font-medium text-sm">
                        Équipements
                    </button>
                    <button @click="activeTab = 'reservations'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'reservations', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'reservations' }" class="py-4 px-1 border-b-2 font-medium text-sm">
                        Réservations
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- Details Tab -->
                <div x-show="activeTab === 'details'" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">Informations générales</h2>
                                <div class="bg-gray-50 p-4 rounded-lg shadow-inner">
                                    <div class="grid grid-cols-1 gap-4">
                                        <div class="flex justify-between border-b border-gray-200 pb-2">
                                            <span class="text-gray-600">Type</span>
                                            <span class="font-medium">{{ $chambre->type_chambre }}</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-2">
                                            <span class="text-gray-600">Capacité</span>
                                            <span class="font-medium">{{ $chambre->capacite }} personnes</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-2">
                                            <span class="text-gray-600">Prix</span>
                                            <span class="font-medium">{{ number_format($chambre->prix, 2) }} FCFA</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-2">
                                            <span class="text-gray-600">Numéro</span>
                                            <span class="font-medium">{{ $chambre->numero ?? 'Non défini' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">Emplacement</h2>
                                <div class="bg-gray-50 p-4 rounded-lg shadow-inner">
                                    <div class="grid grid-cols-1 gap-4">
                                        <div class="flex justify-between border-b border-gray-200 pb-2">
                                            <span class="text-gray-600">Hébergement</span>
                                            <span class="font-medium">{{ $chambre->hebergement->nom }}</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-2">
                                            <span class="text-gray-600">Adresse</span>
                                            <span class="font-medium">{{ $chambre->hebergement->adresse }}</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-2">
                                            <span class="text-gray-600">Ville</span>
                                            <span class="font-medium">{{ $chambre->hebergement->ville ?? 'Non spécifiée' }}</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-200 pb-2">
                                            <span class="text-gray-600">Propriétaire</span>
                                            <span class="font-medium">{{ $chambre->hebergement->proprietaire->nom }} {{ $chambre->hebergement->proprietaire->prenom }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">Description</h2>
                            <div class="bg-gray-50 p-4 rounded-lg shadow-inner">
                                <p class="text-gray-800">{{ $chambre->description }}</p>
                            </div>
                            
                            <h2 class="text-xl font-semibold text-gray-900 mb-2 mt-6">Statistiques</h2>
                            <div class="bg-gray-50 p-4 rounded-lg shadow-inner">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="text-center bg-white p-4 rounded-lg shadow">
                                        <p class="text-gray-500 text-sm">Réservations</p>
                                        <p class="text-3xl font-bold text-blue-600">{{ $chambre->reservations->count() }}</p>
                                    </div>
                                    <div class="text-center bg-white p-4 rounded-lg shadow">
                                        <p class="text-gray-500 text-sm">Revenus</p>
                                        <p class="text-3xl font-bold text-green-600">
                                            {{ number_format($chambre->reservations->sum(function ($reservation) use ($chambre) {
                                                return $chambre->prix * \Carbon\Carbon::parse($reservation->date_fin)->diffInDays($reservation->date_debut);
                                            }), 2) }} FCFA
                                        </p>
                                    </div>
                                    <div class="text-center bg-white p-4 rounded-lg shadow">
                                        <p class="text-gray-500 text-sm">Nuits réservées</p>
                                        <p class="text-3xl font-bold text-purple-600">
                                            {{ $chambre->reservations->sum(function ($reservation) {
                                                return \Carbon\Carbon::parse($reservation->date_fin)->diffInDays($reservation->date_debut);
                                            }) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Photos Tab -->
                <div x-show="activeTab === 'photos'" class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Photos de la chambre</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @forelse($chambre->medias as $index => $media)
                        <div class="relative group">
                            <img @click="openModal({{ $index }})" src="{{ asset('storage/' . $media->url ) }}" alt="{{ $chambre->nom }}" class="w-full h-48 object-cover rounded-lg shadow hover:shadow-lg cursor-pointer transition-all duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 flex items-center justify-center transition-all duration-300 opacity-0 group-hover:opacity-100">
                                <button @click="openModal({{ $index }})" class="bg-white text-gray-800 p-2 rounded-full hover:bg-gray-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center p-8">
                            <p class="text-gray-500">Aucune photo disponible pour cette chambre.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Equipements Tab -->
                <div x-show="activeTab === 'equipements'" class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Équipements de la chambre</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @forelse($chambre->equipements as $equipement)
                        <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-3">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $equipement->nom }}</p>
                                <p class="text-xs text-gray-500">{{ $equipement->description ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center p-8">
                            <p class="text-gray-500">Aucun équipement n'a été ajouté à cette chambre.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Réservations Tab -->
                <div x-show="activeTab === 'reservations'" class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Réservations de la chambre</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date début</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date fin</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nuits</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix total</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($chambre->reservations as $reservation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500 font-medium">{{ substr($reservation->user->prenom, 0, 1) }}{{ substr($reservation->user->nom, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $reservation->user->prenom }} {{ $reservation->user->nom }}</div>
                                                <div class="text-sm text-gray-500">{{ $reservation->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reservation->date_debut)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reservation->date_fin)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($reservation->date_fin)->diffInDays($reservation->date_debut) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($chambre->prix * \Carbon\Carbon::parse($reservation->date_fin)->diffInDays($reservation->date_debut), 2) }} FCFA
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @switch($reservation->statut)
                                                @case('approuvé') bg-green-100 text-green-800 @break
                                                @case('en_attente') bg-yellow-100 text-yellow-800 @break
                                                @case('annulé') bg-red-100 text-red-800 @break
                                                @case('terminé') bg-blue-100 text-blue-800 @break
                                                @case('rejeté') bg-gray-100 text-gray-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch
                                        ">
                                            {{ ucfirst($reservation->statut) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.hebergements.reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Aucune réservation pour cette chambre.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <!-- Image Modal -->
        <div x-show="isModalOpen" @click.away="isModalOpen = false" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
            <div class="max-w-4xl w-full max-h-screen overflow-auto bg-white rounded-lg p-2">
                <div class="relative">
                    <img :src="currentImage" alt="Photo de la chambre" class="w-full h-auto object-contain">
                    <button @click="isModalOpen = false" class="absolute top-4 right-4 bg-white rounded-full p-2 shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="absolute inset-y-0 left-0 flex items-center">
                        <button @click="prevImage" class="bg-white rounded-full p-2 shadow-lg ml-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        <button @click="nextImage" class="bg-white rounded-full p-2 shadow-lg mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection