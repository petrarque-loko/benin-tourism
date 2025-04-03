@extends('layouts.admin')

@section('title', 'Détails de l\'utilisateur')

@section('content')
<div class="container max-w-7xl mx-auto px-4 py-8" x-data="{ showDeleteModal: false }">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Détails de l'utilisateur</h1>
        <div>
            @if($user->status === 'active')
                <span class="px-3 py-1 text-sm rounded-full bg-emerald-100 text-emerald-800 font-medium">
                    <i class="fas fa-circle text-xs mr-1"></i> Actif
                </span>
            @elseif($user->status === 'pending')
                <span class="px-3 py-1 text-sm rounded-full bg-amber-100 text-amber-800 font-medium">
                    <i class="fas fa-clock text-xs mr-1"></i> En attente
                </span>
            @elseif($user->status === 'rejected')
                <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800 font-medium">
                    <i class="fas fa-times-circle text-xs mr-1"></i> Rejeté
                </span>
            @elseif($user->status === 'suspended')
                <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800 font-medium">
                    <i class="fas fa-ban text-xs mr-1"></i> Suspendu
                </span>
            @endif
        </div>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" 
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations personnelles -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-lg">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-white font-semibold">Informations personnelles</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">Nom complet</p>
                            <p class="font-medium text-gray-800">{{ $user->prenom }} {{ $user->nom }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">Type de compte</p>
                            <p class="font-medium text-gray-800">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-user-tag mr-2 text-indigo-500"></i>
                                    {{ $user->role->name }}
                                </span>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium text-gray-800">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-envelope mr-2 text-indigo-500"></i>
                                    {{ $user->email }}
                                </span>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">Téléphone</p>
                            <p class="font-medium text-gray-800">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-phone mr-2 text-indigo-500"></i>
                                    {{ $user->telephone }}
                                </span>
                            </p>
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <p class="text-sm text-gray-500">Adresse</p>
                            <p class="font-medium text-gray-800">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-indigo-500"></i>
                                    {{ $user->adresse }}
                                </span>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">Date d'inscription</p>
                            <p class="font-medium text-gray-800">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-calendar-plus mr-2 text-indigo-500"></i>
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </span>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">Dernière connexion</p>
                            <p class="font-medium text-gray-800">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-clock mr-2 text-indigo-500"></i>
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->format('d/m/Y H:i') }}
                                    @else
                                        Jamais connecté
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Documents fournis -->
            @if($user->role->name !== 'Touriste')
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-lg"
                     x-data="{ expanded: true }">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4 cursor-pointer" @click="expanded = !expanded">
                        <div class="flex justify-between items-center">
                            <h2 class="text-white font-semibold">Documents fournis</h2>
                            <i class="fas" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'" class="text-white"></i>
                        </div>
                    </div>
                    <div class="overflow-hidden transition-all max-h-0 duration-300" :class="{ 'max-h-screen p-6': expanded }">
                        @if($user->documents->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'ajout</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" x-data="{ previewUrl: '' }">
                                        @foreach($user->documents as $document)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-file-alt text-purple-500 mr-2"></i>
                                                        <span class="text-sm font-medium text-gray-900">{{ $document->type }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($document->status === 'approved')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i> Approuvé
                                                        </span>
                                                    @elseif($document->status === 'pending')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-clock mr-1"></i> En attente
                                                        </span>
                                                    @elseif($document->status === 'rejected')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                                            <i class="fas fa-times-circle mr-1"></i> Rejeté
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $document->created_at->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ Storage::url($document->file_path) }}" 
                                                       target="_blank" 
                                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200 inline-flex items-center">
                                                        <i class="fas fa-eye mr-1"></i> Voir
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-folder-open text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500">Aucun document fourni</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Colonne latérale -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-lg">
                <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                    <h2 class="text-white font-semibold">Actions</h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($user->status !== 'pending')
                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @if($user->status === 'active')
                                <button type="submit" class="w-full py-2 px-4 bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 text-white font-medium rounded-lg text-sm flex items-center justify-center transition-colors duration-300">
                                    <i class="fas fa-ban mr-2"></i> Suspendre le compte
                                </button>
                            @elseif ($user->status === 'suspended')
                                <button type="submit" class="w-full py-2 px-4 bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 text-white font-medium rounded-lg text-sm flex items-center justify-center transition-colors duration-300">
                                    <i class="fas fa-check-circle mr-2"></i> Activer le compte
                                </button>
                            @endif
                        </form>
                    @endif
                    
                    @if ($user->status === 'rejected')
                        <button @click="showDeleteModal = true" class="w-full py-2 px-4 bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 text-white font-medium rounded-lg text-sm flex items-center justify-center transition-colors duration-300">
                            <i class="fas fa-trash-alt mr-2"></i> Supprimer le compte
                        </button>
                    @endif
                    
                    <a href="{{ route('admin.users.index') }}" class="w-full py-2 px-4 bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 text-white font-medium rounded-lg text-sm flex items-center justify-center transition-colors duration-300">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md overflow-hidden p-6 text-white">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                    </div>
                    <h3 class="font-semibold text-lg">Statistiques</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-blue-100 text-sm">Statut du compte</p>
                        <p class="font-bold text-lg">{{ ucfirst($user->status) }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm">Membre depuis</p>
                        <p class="font-bold text-lg">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de confirmation de suppression -->
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
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Supprimer le compte</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
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