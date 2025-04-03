<!-- resources/views/admin/hebergements/index.blade.php -->
@extends('layouts.admin')
@section('title', 'Gestion des Hébergements')
@section('content')
<div class="bg-gray-50">
    <div class="min-h-screen" x-data="{
        searchTerm: '',
        typeFilter: 'tous',
        disponibiliteFilter: 'tous',
        sortField: 'nom',
        sortDirection: 'asc',
        currentHebergement: null,
        
        get filteredHebergements() {
            return this.filterHebergements();
        },
        
        filterHebergements() {
            return {{ Illuminate\Support\Js::from($hebergements) }}.filter(hebergement => {
                // Filtrer par terme de recherche
                const matchesSearch = hebergement.nom.toLowerCase().includes(this.searchTerm.toLowerCase()) || 
                                      (hebergement.ville && hebergement.ville.toLowerCase().includes(this.searchTerm.toLowerCase()));
                                      
                // Filtrer par type
                const matchesType = this.typeFilter === 'tous' || hebergement.type_hebergement_id == this.typeFilter;
                
                // Filtrer par disponibilité
                const matchesDisponibilite = this.disponibiliteFilter === 'tous' || 
                                            (this.disponibiliteFilter === 'disponible' && hebergement.disponibilite) || 
                                            (this.disponibiliteFilter === 'indisponible' && !hebergement.disponibilite);
                
                return matchesSearch && matchesType && matchesDisponibilite;
            }).sort((a, b) => {
                let fieldA = a[this.sortField];
                let fieldB = b[this.sortField];
                
                if (this.sortField === 'type_hebergement_id') {
                    const typeA = this.getTypeName(a.type_hebergement_id);
                    const typeB = this.getTypeName(b.type_hebergement_id);
                    return this.sortDirection === 'asc' ? typeA.localeCompare(typeB) : typeB.localeCompare(typeA);
                }
                
                if (this.sortField === 'proprietaire') {
                    fieldA = a.proprietaire ? `${a.proprietaire.nom} ${a.proprietaire.prenom}`.toLowerCase() : '';
                    fieldB = b.proprietaire ? `${b.proprietaire.nom} ${b.proprietaire.prenom}`.toLowerCase() : '';
                } else {
                    fieldA = fieldA?.toString().toLowerCase() || '';
                    fieldB = fieldB?.toString().toLowerCase() || '';
                }
                
                if (fieldA < fieldB) return this.sortDirection === 'asc' ? -1 : 1;
                if (fieldA > fieldB) return this.sortDirection === 'asc' ? 1 : -1;
                return 0;
            });
        },
        
        getTypeName(typeId) {
            const type = {{ Illuminate\Support\Js::from($typesHebergement) }}.find(t => t.id === typeId);
            return type ? type.nom : 'Non défini';
        },
        
        sortBy(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'asc';
            }
        },
        
        showHebergementDetails(hebergement) {
            this.currentHebergement = hebergement;
        }
    }">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Gestion des Hébergements</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md text-gray-700 transition">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.hebergements.statistics') }}" class="bg-blue-100 hover:bg-blue-200 px-4 py-2 rounded-md text-blue-700 transition">
                        Statistiques
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Filters and Search -->
            <div class="bg-white shadow rounded-lg p-5 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                        <input 
                            type="text" 
                            x-model="searchTerm" 
                            id="search" 
                            placeholder="Rechercher par nom, ville..." 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                    
                    <!-- Type filter -->
                    <div>
                        <label for="typeFilter" class="block text-sm font-medium text-gray-700 mb-1">Type d'hébergement</label>
                        <select 
                            x-model="typeFilter" 
                            id="typeFilter" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="tous">Tous les types</option>
                            @foreach($typesHebergement as $type)
                                <option value="{{ $type->id }}">{{ $type->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Disponibilité filter -->
                    <div>
                        <label for="disponibiliteFilter" class="block text-sm font-medium text-gray-700 mb-1">Disponibilité</label>
                        <select 
                            x-model="disponibiliteFilter" 
                            id="disponibiliteFilter" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="tous">Toutes</option>
                            <option value="disponible">Disponible</option>
                            <option value="indisponible">Indisponible</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Flash Message -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Hébergements Table -->
            <div class="bg-white shadow overflow-hidden rounded-lg">
                <div class="flex justify-between items-center p-5 border-b">
                    <h2 class="text-lg font-medium text-gray-900">Liste des hébergements</h2>
                    <span x-text="`${filteredHebergements.length} hébergement(s) trouvé(s)`" class="text-sm text-gray-500"></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" @click="sortBy('nom')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    <div class="flex items-center">
                                        Nom
                                        <span class="ml-1" x-show="sortField === 'nom'">
                                            <template x-if="sortDirection === 'asc'">↑</template>
                                            <template x-if="sortDirection === 'desc'">↓</template>
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" @click="sortBy('type_hebergement_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    <div class="flex items-center">
                                        Type
                                        <span class="ml-1" x-show="sortField === 'type_hebergement_id'">
                                            <template x-if="sortDirection === 'asc'">↑</template>
                                            <template x-if="sortDirection === 'desc'">↓</template>
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" @click="sortBy('ville')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    <div class="flex items-center">
                                        Ville
                                        <span class="ml-1" x-show="sortField === 'ville'">
                                            <template x-if="sortDirection === 'asc'">↑</template>
                                            <template x-if="sortDirection === 'desc'">↓</template>
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" @click="sortBy('proprietaire')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    <div class="flex items-center">
                                        Propriétaire
                                        <span class="ml-1" x-show="sortField === 'proprietaire'">
                                            <template x-if="sortDirection === 'asc'">↑</template>
                                            <template x-if="sortDirection === 'desc'">↓</template>
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Disponibilité
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="hebergement in filteredHebergements" :key="hebergement.id">
                                <tr class="hover:bg-gray-50" @click="showHebergementDetails(hebergement)">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900" x-text="hebergement.nom"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500" x-text="getTypeName(hebergement.type_hebergement_id)"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500" x-text="hebergement.ville || 'Non spécifié'"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500" x-text="hebergement.proprietaire ? `${hebergement.proprietaire.nom} ${hebergement.proprietaire.prenom}` : 'Non assigné'"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span 
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                            :class="hebergement.disponibilite ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            x-text="hebergement.disponibilite ? 'Disponible' : 'Indisponible'"
                                        ></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a :href="`{{ route('admin.hebergements.show', '') }}/${hebergement.id}`" class="text-blue-600 hover:text-blue-900">
                                                Détails
                                            </a>
                                            <form :action="`{{ route('admin.hebergements.toggle-visibility', 'ID_PLACEHOLDER') }}`.replace('ID_PLACEHOLDER', hebergement.id)" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                                    <span x-text="hebergement.disponibilite ? 'Masquer' : 'Afficher'"></span>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <!-- Empty state when no results -->
                            <tr x-show="filteredHebergements.length === 0">
                                <td colspan="6" class="px-6 py-10 text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="mt-2 text-sm">Aucun hébergement trouvé</p>
                                        <p class="mt-1 text-sm text-gray-400">Essayez de modifier vos filtres</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <!-- Modal de détails d'hébergement -->
        <div
            x-show="currentHebergement !== null"
            x-cloak
            class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="currentHebergement = null"></div>
            
            <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 z-10" x-show="currentHebergement !== null">
                <div class="px-6 py-4 border-b">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900" x-text="currentHebergement ? currentHebergement.nom : ''"></h3>
                        <button @click="currentHebergement = null" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Type</h4>
                                <p class="mt-1" x-text="currentHebergement ? getTypeName(currentHebergement.type_hebergement_id) : ''"></p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Adresse</h4>
                                <p class="mt-1" x-text="currentHebergement ? currentHebergement.adresse : ''"></p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Ville</h4>
                                <p class="mt-1" x-text="currentHebergement ? currentHebergement.ville || 'Non spécifié' : ''"></p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Prix</h4>
                                <p class="mt-1" x-text="currentHebergement ? `${currentHebergement.prix_min} - ${currentHebergement.prix_max} FCFA` : ''"></p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Statut</h4>
                                <p class="mt-1">
                                    <span 
                                        x-show="currentHebergement" 
                                        :class="currentHebergement && currentHebergement.disponibilite ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="px-2 py-1 text-xs font-semibold rounded-full"
                                        x-text="currentHebergement && currentHebergement.disponibilite ? 'Disponible' : 'Indisponible'"
                                    ></span>
                                </p>
                            </div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Propriétaire</h4>
                                <p class="mt-1" x-text="currentHebergement && currentHebergement.proprietaire ? `${currentHebergement.proprietaire.nom} ${currentHebergement.proprietaire.prenom}` : 'Non assigné'"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-500">Description</h4>
                        <p class="mt-1 text-sm text-gray-600" x-text="currentHebergement ? currentHebergement.description : ''"></p>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <a 
                        x-show="currentHebergement"
                        :href="`{{ route('admin.hebergements.show', '') }}/${currentHebergement ? currentHebergement.id : ''}`" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Voir les détails complets
                    </a>
                    <button 
                        @click="currentHebergement = null" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>  
    <script>
        // Configuration du CSRF token pour les requêtes AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            window.axios = window.axios || {};
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        });
    </script>
@endsection