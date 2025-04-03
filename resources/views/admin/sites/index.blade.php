@extends('layouts.admin')
@section('title', 'Sites Touristiques')
@section('content')

<div class="bg-cover bg-center bg-fixed min-h-screen py-6" >
    



    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Sites Touristiques</h1>
        <a href="{{ route('admin.sites.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Ajouter un site
        </a>
    </div>

    <div x-data="{ showAlert: {{ session()->has('success') ? 'true' : 'false' }} }">
        <div x-cloak x-show="showAlert" x-transition:enter="transition ease-out duration-300" 
            x-transition:enter-start="opacity-0 transform -translate-y-2" 
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300" 
            x-transition:leave-start="opacity-100 transform translate-y-0" 
            x-transition:leave-end="opacity-0 transform -translate-y-2" 
            class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md relative">
            <div class="flex items-center">
                <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p>{{ session('success') }}</p>
            </div>
            <button @click="showAlert = false" type="button" class="absolute top-0 right-0 p-2 text-green-600 hover:text-green-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden" x-data="sitesTable()">
        <div class="p-4 border-b">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                <div class="relative">
                    <input type="text" x-model="search" 
                        @input="filterTable()" 
                        placeholder="Rechercher..." 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <select x-model="categoryFilter" @change="filterTable()" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->nom }}">{{ $categorie->nom }}</option>
                        @endforeach
                    </select>
                    <select x-model="perPage" @change="changePerPage()" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="10">10 par page</option>
                        <option value="25">25 par page</option>
                        <option value="50">50 par page</option>
                        <option value="100">100 par page</option>
                    </select>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <template x-for="column in columns" :key="column.key">
                                <th @click="sortTable(column.key)" 
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex items-center">
                                        <span x-text="column.label"></span>
                                        <span class="ml-1">
                                            <template x-if="sortColumn === column.key && sortDirection === 'asc'">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            </template>
                                            <template x-if="sortColumn === column.key && sortDirection === 'desc'">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </template>
                                        </span>
                                    </div>
                                </th>
                            </template>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="(site, index) in paginatedSites" :key="site.id">
                        <tr class="hover:bg-gray-50 transition-colors duration-150" :class="{'bg-gray-50': index % 2 === 0}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="site.id"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="site.nom"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="site.categorie?.nom || 'Non catégorisé'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="site.localisation"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(site.created_at)"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 space-x-2">
                                <!-- Bouton Voir -->
                                <a :href="`/admin/sites/${site.id}`"
                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                <!-- Bouton Modifier -->
                                <a :href="`/admin/sites/${site.id}/edit`"
                                class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded hover:bg-green-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- Bouton Supprimer -->
                                <button @click="confirmDelete(site)"
                                        class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 rounded hover:bg-red-200 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>

                        <template x-if="paginatedSites.length === 0">
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucun site trouvé.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button @click="prevPage()" :disabled="currentPage === 1" 
                        :class="{'opacity-50 cursor-not-allowed': currentPage === 1}" 
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Précédent
                    </button>
                    <button @click="nextPage()" :disabled="currentPage >= totalPages" 
                        :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages}" 
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Suivant
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de 
                            <span class="font-medium" x-text="paginationStart()"></span>
                            à 
                            <span class="font-medium" x-text="paginationEnd()"></span>
                            sur 
                            <span class="font-medium" x-text="filteredSites.length"></span>
                            résultats
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button @click="prevPage()" :disabled="currentPage === 1" 
                                :class="{'opacity-50 cursor-not-allowed': currentPage === 1}" 
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Précédent</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <template x-for="page in pagesArray()" :key="page">
                                <button @click="goToPage(page)" 
                                    :class="{'bg-blue-50 border-blue-500 text-blue-600': page === currentPage, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': page !== currentPage}" 
                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    <span x-text="page"></span>
                                </button>
                            </template>
                            <button @click="nextPage()" :disabled="currentPage >= totalPages" 
                                :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages}" 
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Suivant</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal de confirmation de suppression -->
        <div x-cloak x-show="modalOpen" 
            @keydown.escape.window="modalOpen = false"
            class="fixed inset-0 overflow-y-auto z-50">
            
            <div x-cloak x-show="modalOpen" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity" 
                @click="modalOpen = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div x-cloak x-show="modalOpen" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full mx-auto mt-32 p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Confirmer la suppression</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Êtes-vous sûr de vouloir supprimer le site "<span x-text="siteToDelete?.nom"></span>" ? 
                        Cette action est irréversible.
                    </p>
                </div>
                <div class="mt-5 sm:mt-6 flex justify-end space-x-3">
                    <button @click="modalOpen = false" 
                        type="button" 
                        class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        Annuler
                    </button>
                    <form :action="`{{ route('admin.sites.destroy', '') }}/${siteToDelete?.id}`" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
</div>

@endsection  


@push('scripts')
<script>
function sitesTable() {
    return {
        sites: @json($sites),
        columns: [
            { key: 'id', label: 'ID' },
            { key: 'nom', label: 'Nom' },
            { key: 'categorie', label: 'Catégorie' },
            { key: 'localisation', label: 'Localisation' },
            { key: 'created_at', label: 'Date de création' }
        ],
        search: '',
        sortColumn: 'id',
        sortDirection: 'asc',
        currentPage: 1,
        perPage: 10,
        categoryFilter: '',
        
        get filteredSites() {
            let filtered = [...this.sites];
            
            // Filtre de recherche
            if (this.search.trim() !== '') {
                const searchLower = this.search.toLowerCase();
                filtered = filtered.filter(site => 
                    site.id.toString().includes(searchLower) ||
                    site.nom.toLowerCase().includes(searchLower) ||
                    (site.categorie?.nom || '').toLowerCase().includes(searchLower) ||
                    site.localisation.toLowerCase().includes(searchLower)
                );
            }
            
            // Filtre par catégorie
            if (this.categoryFilter !== '') {
                filtered = filtered.filter(site => 
                    site.categorie?.nom === this.categoryFilter
                );
            }
            
            // Tri
            filtered.sort((a, b) => {
                let valueA, valueB;
                
                if (this.sortColumn === 'categorie') {
                    valueA = a.categorie?.nom || '';
                    valueB = b.categorie?.nom || '';
                } else {
                    valueA = a[this.sortColumn];
                    valueB = b[this.sortColumn];
                }
                
                if (typeof valueA === 'string') {
                    valueA = valueA.toLowerCase();
                }
                if (typeof valueB === 'string') {
                    valueB = valueB.toLowerCase();
                }
                
                if (valueA < valueB) {
                    return this.sortDirection === 'asc' ? -1 : 1;
                }
                if (valueA > valueB) {
                    return this.sortDirection === 'asc' ? 1 : -1;
                }
                return 0;
            });
            
            return filtered;
        },
        
        get paginatedSites() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredSites.slice(start, start + this.perPage);
        },
        
        get totalPages() {
            return Math.ceil(this.filteredSites.length / this.perPage);
        },
        
        sortTable(column) {
            if (this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }
        },
        
        filterTable() {
            this.currentPage = 1;
        },
        
        changePerPage() {
            this.currentPage = 1;
        },
        
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        
        goToPage(page) {
            this.currentPage = page;
        },
        
        paginationStart() {
            return this.filteredSites.length === 0 ? 0 : (this.currentPage - 1) * this.perPage + 1;
        },
        
        paginationEnd() {
            return Math.min(this.currentPage * this.perPage, this.filteredSites.length);
        },
        
        pagesArray() {
            const pages = [];
            const maxPages = Math.min(5, this.totalPages);
            
            let startPage = this.currentPage - Math.floor(maxPages / 2);
            startPage = Math.max(1, startPage);
            startPage = Math.min(startPage, this.totalPages - maxPages + 1);
            
            for (let i = 0; i < maxPages && startPage + i <= this.totalPages; i++) {
                pages.push(startPage + i);
            }
            
            return pages;
        },
        
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR');
        },
        modalOpen: false,
        siteToDelete: null,

        confirmDelete(site) {
            this.siteToDelete = site;
            this.modalOpen = true;
        }
    };
}
</script>
@endpush