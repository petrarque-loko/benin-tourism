@extends('layouts.admin')

@section('title', 'Examen d\'inscription')

@section('content')

<div class="container-fluid py-6" x-data="{ showRejectionModal: false }">
    <div class="flex flex-wrap items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg mr-3">
                <i class="fas fa-user-check"></i>
            </span>
            Examen d'inscription
        </h1>
        <a href="{{ route('admin.users.pending') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 transition-all duration-300 hover:bg-gray-50 hover:text-indigo-600 flex items-center group">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> 
            Retour aux inscriptions
        </a>
    </div>
    
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-init="setTimeout(() => show = false, 5000)" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-4"
             class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md shadow-md">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="show = false" class="text-green-500 hover:text-green-700 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-200 hover:shadow-md mb-6 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <h2 class="font-bold text-indigo-600 flex items-center text-lg">
                        <i class="fas fa-user-circle mr-2"></i>
                        Informations personnelles
                    </h2>
                    @if($user->status === 'active')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Actif</span>
                    @elseif($user->status === 'pending')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">En attente</span>
                    @elseif($user->status === 'rejected')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Rejeté</span>
                    @elseif($user->status === 'suspended')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Suspendu</span>
                    @endif
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Nom complet</p>
                            <p class="font-semibold text-gray-800">{{ $user->prenom }} {{ $user->nom }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Type de compte</p>
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                {{ $user->role->name }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Email</p>
                            <a href="mailto:{{ $user->email }}" class="font-semibold text-gray-800 flex items-center group hover:text-indigo-600 transition-colors duration-200">
                                <i class="fas fa-envelope text-gray-400 mr-2 group-hover:text-indigo-600"></i>
                                <span>{{ $user->email }}</span>
                            </a>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Téléphone</p>
                            <a href="tel:{{ $user->telephone }}" class="font-semibold text-gray-800 flex items-center group hover:text-indigo-600 transition-colors duration-200">
                                <i class="fas fa-phone-alt text-gray-400 mr-2 group-hover:text-indigo-600"></i>
                                <span>{{ $user->telephone }}</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Adresse</p>
                        <p class="font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                            <span>{{ $user->adresse }}</span>
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Date d'inscription</p>
                            <div class="font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                <span class="text-xs text-gray-500 ml-2">({{ $user->created_at->diffForHumans() }})</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Dernière mise à jour</p>
                            <div class="font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                <span>{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-200 hover:shadow-md overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="font-bold text-indigo-600 flex items-center text-lg">
                        <i class="fas fa-file-alt mr-2"></i>
                        Documents fournis
                    </h2>
                </div>
                <div class="p-6">
                    @if($user->documents->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($user->documents as $document)
                                <div 
                                    class="border border-gray-200 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
                                    x-data="{ hover: false }"
                                    @mouseenter="hover = true"
                                    @mouseleave="hover = false"
                                >
                                    <div class="p-4">
                                        <div class="flex items-start mb-2">
                                            <div class="p-2 rounded-md" :class="hover ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="font-medium text-gray-900 truncate">{{ $document->type }}</h3>
                                                <p class="text-xs text-gray-500 truncate mt-1">{{ basename($document->file_path) }}</p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2 mt-4">
                                            <a href="{{ Storage::url($document->file_path) }}" target="_blank" 
                                               class="flex-1 flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                                <i class="fas fa-eye mr-2"></i> Voir
                                            </a>
                                            <a href="{{ Storage::url($document->file_path) }}" download 
                                               class="flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                                <i class="fas fa-download mr-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <div class="p-4 bg-gray-100 text-gray-400 rounded-full mb-4 animate-pulse">
                                <i class="fas fa-folder-open text-4xl"></i>
                            </div>
                            <p class="text-gray-500">Aucun document fourni par l'utilisateur</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-200 hover:shadow-md sticky top-4">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="font-bold text-indigo-600 flex items-center text-lg">
                        <i class="fas fa-tasks mr-2"></i>
                        Actions
                    </h2>
                </div>
                @if($user->status === 'pending')
                <div class="p-6">
                    <div class="bg-blue-50 rounded-lg p-4 mb-6 flex items-start">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-info-circle"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800">Vérification requise</h3>
                            <div class="mt-1 text-sm text-blue-700">
                                Veuillez examiner attentivement les informations fournies avant d'approuver ou rejeter cette inscription.
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" class="mb-4">
                        @csrf
                        <button type="submit" 
                                class="w-full px-4 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center">
                            <i class="fas fa-check mr-2"></i> Approuver l'inscription
                        </button>
                    </form>
                    
                    <button type="button" 
                            @click="showRejectionModal = true"
                            class="w-full px-4 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg mb-6 flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i> Rejeter l'inscription
                    </button>
                    
                    <div class="border-t border-gray-200 my-6 pt-6">
                        <div class="space-y-3">
                            <a href="{{ route('admin.users.pending') }}" 
                               class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                <i class="fas fa-users mr-2"></i> Liste des inscriptions en attente
                            </a>
                            <a href="{{ route('admin.users.index') }}" 
                               class="w-full flex items-center justify-center px-4 py-2 border border-indigo-300 shadow-sm text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                <i class="fas fa-list mr-2"></i> Tous les utilisateurs
                            </a>
                        </div>
                    </div>
                </div>
                @else 
                <div class="p-6">
                    <div class="bg-blue-50 rounded-lg p-4 mb-4 flex items-start">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-info-circle"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800">Aucune action requise</h3>
                            <div class="mt-1 text-sm text-blue-700">
                                La demande d'inscription a été déjà examinée.
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Modal de rejet avec Alpine.js -->
    <div x-show="showRejectionModal" 
         class="fixed inset-0 overflow-y-auto z-50"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75" @click="showRejectionModal = false"></div>
            </div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="showRejectionModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <form action="{{ route('admin.users.reject', $user->id) }}" method="POST">
                    @csrf
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                                <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 mr-2 flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                Rejeter l'inscription
                            </h3>
                            <button type="button" @click="showRejectionModal = false" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            <div class="mb-4">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">Motif du rejet</label>
                                <textarea 
                                    id="rejection_reason" 
                                    name="rejection_reason" 
                                    rows="4" 
                                    required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md transition-colors duration-200"
                                    placeholder="Veuillez préciser les raisons du rejet..."
                                    x-data="{ text: '' }"
                                    x-model="text"
                                    x-init="$watch('text', value => { if (value.trim().length > 0) { $el.classList.add('border-green-300'); } else { $el.classList.remove('border-green-300'); } })"></textarea>
                                <p class="mt-1 text-sm text-gray-500">
                                    <i class="fas fa-envelope-open-text mr-1"></i>
                                    Ce motif sera communiqué à l'utilisateur par email.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-5 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i> Confirmer le rejet
                        </button>
                        <button type="button" @click="showRejectionModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<!-- CDN de Tailwind CSS - retirer si déjà inclus dans le layout -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<!-- CDN d'icônes modernes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

<style>
    /* Animation hover personnalisée */
    .document-hover-effect {
        transition: all 0.3s ease;
    }
    
    .document-hover-effect:hover {
        transform: translateY(-3px);
    }

    /* Pulse animation for custom elements */
    @keyframes gentle-pulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.05); }
        100% { opacity: 1; transform: scale(1); }
    }
    
    .custom-pulse {
        animation: gentle-pulse 2s infinite;
    }
</style>
@endpush

@push('scripts')
<!-- Alpine.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.2/dist/cdn.min.js" defer></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation pour cards au scroll
        const animateOnScroll = () => {
            const cards = document.querySelectorAll('.bg-white.rounded-xl');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('animate__animated', 'animate__fadeInUp');
                }, index * 100);
            });
        };
        
        // Init animations
        animateOnScroll();
        
        // Animation pour hover sur boutons
        const actionButtons = document.querySelectorAll('button, a');
        actionButtons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.classList.add('shadow-md');
            });
            button.addEventListener('mouseleave', function() {
                this.classList.remove('shadow-md');
            });
        });
    });
</script>
@endpush