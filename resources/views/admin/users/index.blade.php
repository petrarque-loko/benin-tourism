@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')

@section('content')

<div x-data="{ 
    showDeleteModal: false,
    userToDelete: null,
    openDropdown: null,
    showSuccess: {{ session('success') ? 'true' : 'false' }}
}" 
class="container-fluid py-6 bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen px-4 sm:px-6">
    <!-- Title and Heading Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800 bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600">
            <span class="inline-block transform hover:scale-105 transition-transform duration-300">Gestion des utilisateurs</span>
        </h1>
        
        <a href="{{ route('admin.users.pending') }}" 
           class="mt-4 md:mt-0 group flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-amber-400 to-amber-500 text-white font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
            <i class="fas fa-clock mr-2"></i>
            <span>Inscriptions en attente</span>
            <span class="ml-2 px-2 py-1 bg-white text-amber-600 rounded-full group-hover:bg-amber-100 transition-colors duration-300">
                {{ App\Models\User::where('status', 'pending')->count() }}
            </span>
        </a>
    </div>  
    
    <!-- Success Alert -->
    <div x-show="showSuccess" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4"
         class="mb-6 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-lg shadow-md flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-2xl mr-3"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button @click="showSuccess = false" class="focus:outline-none text-white hover:text-emerald-100 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    
    <!-- Main Card -->
    <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-blue-100 transition-all duration-300 hover:shadow-2xl">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-users mr-2"></i>
                <span>Liste des utilisateurs</span>
            </h2>
            <div class="mt-2 sm:mt-0 flex items-center space-x-2">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-2 rounded-lg text-white flex items-center transition-colors duration-200">
                        <i class="fas fa-filter mr-1"></i>
                        <span>Filtrer</span>
                        <i class="fas fa-chevron-down ml-1 text-sm"></i>
                    </button>
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-check-circle mr-2 text-green-500"></i>Actifs
                        </a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-clock mr-2 text-amber-500"></i>En attente
                        </a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-ban mr-2 text-red-500"></i>Suspendus
                        </a>
                    </div>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-2 rounded-lg text-white flex items-center transition-colors duration-200">
                        <i class="fas fa-cog mr-1"></i>
                        <span>Options</span>
                        <i class="fas fa-chevron-down ml-1 text-sm"></i>
                    </button>
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-download mr-2"></i>Exporter CSV
                        </a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-print mr-2"></i>Imprimer
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div class="bg-blue-50 px-6 py-3 border-b border-blue-100">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-blue-400"></i>
                </div>
                <input type="text" id="tableSearch" placeholder="Rechercher un utilisateur..." 
                    class="block w-full pl-10 pr-4 py-2 rounded-lg border border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    x-on:input="filterTable()">
            </div>
        </div>
        
        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table id="usersTable" class="min-w-full divide-y divide-blue-100">
                <thead>
                    <tr class="bg-blue-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                            <div class="flex items-center">
                                <span>Nom</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                            <div class="flex items-center">
                                <span>Email</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                            <div class="flex items-center">
                                <span>Téléphone</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                            <div class="flex items-center">
                                <span>Rôle</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                            <div class="flex items-center">
                                <span>Statut</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider cursor-pointer hover:bg-blue-100 transition-colors">
                            <div class="flex items-center">
                                <span>Inscription</span>
                                <i class="fas fa-sort ml-1"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-blue-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->prenom }} {{ $user->nom }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $user->telephone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium">
                                    @if($user->role->name == 'Admin')
                                        <span class="px-2 py-1 rounded-full bg-purple-100 text-purple-800">
                                            <i class="fas fa-user-shield mr-1"></i>Admin
                                        </span>
                                    @elseif($user->role->name == 'Modérateur')
                                        <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-user-edit mr-1"></i>Modérateur
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-user mr-1"></i>{{ $user->role->name }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <span class="h-2 w-2 mr-1 bg-green-500 rounded-full animate-pulse"></span>
                                        Actif
                                    </span>
                                @elseif($user->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        <span class="h-2 w-2 mr-1 bg-amber-500 rounded-full"></span>
                                        En attente
                                    </span>
                                @elseif($user->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Rejeté
                                    </span>
                                @elseif($user->status === 'suspended')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-ban mr-1"></i>
                                        Suspendu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $user->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-1" x-data="{ open: false }">
                                    <a href="{{ route('admin.users.show', $user->id) }}" 
                                       class="text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 p-2 rounded-lg transform hover:scale-105 transition-all duration-200 tooltip" data-tip="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($user->status !== 'pending')
                                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            @if($user->status === 'active')
                                                <button type="submit" 
                                                    class="text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 p-2 rounded-lg transform hover:scale-105 transition-all duration-200 tooltip" 
                                                    data-tip="Suspendre le compte"
                                                    onclick="animateButton(this)">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            @elseif ($user->status === 'suspended')
                                                <button type="submit" 
                                                    class="text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 p-2 rounded-lg transform hover:scale-105 transition-all duration-200 tooltip" 
                                                    data-tip="Activer le compte"
                                                    onclick="animateButton(this)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                    
                                    @if ($user->status === 'rejected')
                                        <button 
                                            @click="userToDelete = {{ $user->id }}; showDeleteModal = true"
                                            class="text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 p-2 rounded-lg transform hover:scale-105 transition-all duration-200 tooltip" 
                                            data-tip="Supprimer le compte">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif
                                    
                                    <button 
                                        @click="open = !open; openDropdown = open ? {{ $user->id }} : null" 
                                        class="text-white bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 p-2 rounded-lg transform hover:scale-105 transition-all duration-200">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <div x-show="open && openDropdown === {{ $user->id }}" 
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg py-1 right-0" 
                                         style="margin-top: 3rem;">
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                            <i class="fas fa-envelope mr-2 text-blue-500"></i>Envoyer un email
                                        </a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                            <i class="fas fa-key mr-2 text-amber-500"></i>Réinitialiser mot de passe
                                        </a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                            <i class="fas fa-user-edit mr-2 text-green-500"></i>Modifier le profil
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-blue-100">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $users->links() }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de <span class="font-medium">{{ $users->firstItem() }}</span> à <span class="font-medium">{{ $users->lastItem() }}</span> sur <span class="font-medium">{{ $users->total() }}</span> utilisateurs
                        </p>
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Supprimer l'utilisateur</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form :action="`{{ route('admin.users.destroy', '') }}/${userToDelete}`" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Supprimer
                        </button>
                    </form>
                    <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

<style>
    /* Tooltip styles */
    .tooltip {
        position: relative;
    }
    
    .tooltip:hover:after {
        content: attr(data-tip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        border-radius: 4px;
        padding: 4px 8px;
        white-space: nowrap;
        font-size: 12px;
        pointer-events: none;
        opacity: 0;
        animation: fadeIn 0.3s forwards;
    }
    
    @keyframes fadeIn {
        to {
            opacity: 1;
            bottom: calc(100% + 5px);
        }
    }
    
    /* Table hover effect */
    tr:hover td {
        background-color: rgba(239, 246, 255, 0.7);
    }
    
    /* Animation for status badge */
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    /* Hover effect for buttons */
    button:hover, a:hover {
        filter: brightness(1.1);
    }
    
    /* Custom animation for pagination */
    .pagination-item {
        transition: all 0.2s;
    }
    
    .pagination-item:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
<script>
    // Button click animation
    function animateButton(button) {
        button.classList.add('animate-press');
        setTimeout(() => {
            button.classList.remove('animate-press');
        }, 200);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Table search functionality
        window.filterTable = function() {
            const input = document.getElementById('tableSearch');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('usersTable');
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 1; i < rows.length; i++) {
                let found = false;
                const cells = rows[i].getElementsByTagName('td');
                
                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent || cells[j].innerText;
                    if (cellText.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
                
                if (found) {
                    rows[i].style.display = '';
                    // Add a subtle animation to matching rows
                    rows[i].style.backgroundColor = '';
                    setTimeout(() => {
                        rows[i].style.backgroundColor = 'rgba(219, 234, 254, 0.3)';
                        setTimeout(() => {
                            rows[i].style.backgroundColor = '';
                        }, 500);
                    }, 50 * i);
                } else {
                    rows[i].style.display = 'none';
                }
            }
        };
        
        // Table sorting functionality
        const headers = document.querySelectorAll('#usersTable th');
        
        headers.forEach(header => {
            header.addEventListener('click', function() {
                const table = document.getElementById('usersTable');
                const columnIndex = Array.from(header.parentElement.children).indexOf(header);
                const rows = Array.from(table.querySelectorAll('tbody tr'));
                
                // Skip the actions column
                if (columnIndex === 6) return;
                
                const currentSort = header.getAttribute('data-sort') || 'none';
                
                // Reset all headers
                headers.forEach(h => {
                    if (h !== header) h.setAttribute('data-sort', 'none');
                    h.querySelectorAll('i').forEach(icon => {
                        icon.className = 'fas fa-sort ml-1';
                    });
                });
                
                // Toggle sort direction
                let nextSort;
                if (currentSort === 'none' || currentSort === 'desc') {
                    nextSort = 'asc';
                    header.querySelector('i').className = 'fas fa-sort-up ml-1';
                } else {
                    nextSort = 'desc';
                    header.querySelector('i').className = 'fas fa-sort-down ml-1';
                }
                
                header.setAttribute('data-sort', nextSort);
                
                // Sort the rows
                rows.sort((a, b) => {
                    let textA = a.children[columnIndex].textContent.trim();
                    let textB = b.children[columnIndex].textContent.trim();
                    
                    // Check if we're dealing with a date column
                    if (columnIndex === 5) {
                        const dateA = parseDate(textA);
                        const dateB = parseDate(textB);
                        return nextSort === 'asc' ? dateA - dateB : dateB - dateA;
                    }
                    
                    // Regular string comparison
                    const comparison = textA.localeCompare(textB, undefined, {numeric: true, sensitivity: 'base'});
                    return nextSort === 'asc' ? comparison : -comparison;
                });
                    // Animate and reorder rows
                const tbody = table.querySelector('tbody');
                rows.forEach((row, index) => {
                    // Apply a staggered animation effect
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(-10px)';
                    
                    setTimeout(() => {
                        tbody.appendChild(row);
                        // Animate row back in
                        setTimeout(() => {
                            row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            row.style.opacity = '1';
                            row.style.transform = 'translateY(0)';
                        }, 10);
                    }, index * 30);
                });
            });
        });
        
        // Function to parse date in format dd/mm/yyyy
        function parseDate(dateStr) {
            const parts = dateStr.split('/');
            return new Date(parts[2], parts[1] - 1, parts[0]).getTime();
        }
        
        // Initialize tooltips for dynamic content
        function initTooltips() {
            const tooltips = document.querySelectorAll('.tooltip');
            tooltips.forEach(tooltip => {
                tooltip.addEventListener('mouseenter', function() {
                    // Ensure tooltip is positioned correctly for newly added elements
                    const tip = this.getAttribute('data-tip');
                    if (!tip) return;
                    
                    // Force recalculation of tooltip position
                    this.classList.add('tooltip-active');
                });
                
                tooltip.addEventListener('mouseleave', function() {
                    this.classList.remove('tooltip-active');
                });
            });
        }
        
        // Call initTooltips on page load
        initTooltips();
        
        // Enhance pagination with smooth transitions
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.classList.add('pagination-item');
        });
    });
</script>
@endpush