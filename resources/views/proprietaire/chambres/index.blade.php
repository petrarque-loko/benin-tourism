@extends('layouts.proprietaire')

@section('title', 'Gestion des chambres - ' . $hebergement->nom)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Chambres - {{ $hebergement->nom }}</h1>
            <p class="text-gray-600">Gérez les chambres de votre hébergement</p>
        </div>
        <a href="{{ route('proprietaire.hebergements.chambres.create', $hebergement->id) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ajouter une chambre
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div x-data="{
            chambres: [],
            sortField: 'nom',
            sortDirection: 'asc',
            search: '',
            
            init() {
                this.chambres = {{ Illuminate\Support\Js::from($chambres) }};
            },
            
            sort(field) {
                if (this.sortField === field) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortField = field;
                    this.sortDirection = 'asc';
                }
                
                this.sortChambres();
            },
            
            sortChambres() {
                this.chambres.sort((a, b) => {
                    let modifier = this.sortDirection === 'asc' ? 1 : -1;
                    if (a[this.sortField] < b[this.sortField]) return -1 * modifier;
                    if (a[this.sortField] > b[this.sortField]) return 1 * modifier;
                    return 0;
                });
            },
            
            filteredChambres() {
                if (!this.search) return this.chambres;
                
                return this.chambres.filter(chambre => {
                    return chambre.nom.toLowerCase().includes(this.search.toLowerCase()) ||
                           chambre.type_chambre.toLowerCase().includes(this.search.toLowerCase()) ||
                           chambre.numero?.toLowerCase().includes(this.search.toLowerCase());
                });
            }
        }" class="w-full">
            <div class="p-4 border-b">
                <div class="flex items-center">
                    <div class="relative flex-grow">
                        <input 
                            x-model="search"
                            type="text" 
                            placeholder="Rechercher une chambre..." 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <svg class="absolute right-3 top-2.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th @click="sort('numero')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                N°
                                <span class="ml-1">
                                    <template x-if="sortField === 'numero' && sortDirection === 'asc'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    </template>
                                    <template x-if="sortField === 'numero' && sortDirection === 'desc'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </template>
                                </span>
                            </div>
                        </th>
                        <th @click="sort('nom')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Nom
                                <span class="ml-1">
                                    <template x-if="sortField === 'nom' && sortDirection === 'asc'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    </template>
                                    <template x-if="sortField === 'nom' && sortDirection === 'desc'">
                                        <svg class="w-4 h-4" fill="none" strokproprietairee="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </template>
                                </span>
                            </div>
                        </th>
                        <th @click="sort('type_chambre')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Type
                                <span class="ml-1">
                                    <template x-if="sortField === 'type_chambre' && sortDirection === 'asc'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    </template>
                                    <template x-if="sortField === 'type_chambre' && sortDirection === 'desc'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </template>
                                </span>
                            </div>
                        </th>
                        <th @click="sort('capacite')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Capacité
                                <span class="ml-1">
                                    <template x-if="sortField === 'capacite' && sortDirection === 'asc'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    </template>
                                    <template x-if="sortField === 'capacite' && sortDirection === 'desc'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </template>
                                </span>
                            </div>
                        </th>
                        <th @click="sort('prix')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Prix
                                <span class="ml-1">
                                    <template x-if="sortField === 'prix' && sortDirection === 'asc'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    </template>
                                    <template x-if="sortField === 'prix' && sortDirection === 'desc'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </template>
                                </span>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="chambre in filteredChambres()" :key="chambre.id">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="chambre.numero || '-'"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <template x-if="chambre.medias && chambre.medias.length > 0">
                                            <img class="h-10 w-10 rounded-full object-cover" :src="'/storage/' + chambre.medias[0].url" alt="Chambre">
                                        </template>
                                        <template x-if="!chambre.medias || chambre.medias.length === 0">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                </svg>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900" x-text="chambre.nom"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="chambre.type_chambre"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-500 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span x-text="chambre.capacite"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span x-text="chambre.prix + ' €'"></span>
                                <span class="text-xs text-gray-400">/nuit</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                <span 
                                        :class="chambre.est_disponible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        <span x-text="chambre.est_disponible ? 'Disponible' : 'Indisponible'"></span>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a :href="`{{ url('proprietaire/hebergements') }}/{{{ $hebergement->id }}}/chambres/${chambre.id}`" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a :href="`{{ url('proprietaire/hebergements') }}/{{{ $hebergement->id }}}/chambres/${chambre.id}/edit`" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form :action="`{{ url('proprietaire/hebergements') }}/{{{ $hebergement->id }}}/chambres/${chambre.id}/toggle-availability`" 
                                         method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                :class="chambre.est_disponible ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'"
                                                title="Changer la disponibilité">
                                            <template x-if="chambre.est_disponible">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </template>
                                            <template x-if="!chambre.est_disponible">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </template>
                                        </button>
                                    </form>
                                    <form :action="`{{ url('proprietaire/hebergements') }}/{{{ $hebergement->id }}}/chambres/${chambre.id}`" 
                                         method="POST" class="inline" 
                                         onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette chambre ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-if="filteredChambres().length === 0">
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <span class="mt-2 block font-medium">Aucune chambre trouvée</span>
                                    <span class="mt-1 text-sm">Ajoutez votre première chambre ou modifiez votre recherche</span>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection