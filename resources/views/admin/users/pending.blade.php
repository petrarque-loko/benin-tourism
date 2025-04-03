@extends('layouts.admin')

@section('title', 'Inscriptions en attente')

@section('content')
<!-- Main Content Section -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8"



<div class="bg-cover bg-center bg-fixed min-h-screen py-6" 
     style="background-image: url('/images/background.jpg');">
    <div " 
         x-data="siteEditor()">
        <!-- Reste du contenu... -->
    </div>




    <div class="max-w-7xl mx-auto">
        <!-- Header Section with Animation -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8" 
             x-data="{ showHeader: false }"
             x-init="setTimeout(() => showHeader = true, 100)">
            <div class="transform transition-all duration-500 ease-in-out" 
                 :class="showHeader ? 'translate-x-0 opacity-100' : '-translate-x-8 opacity-0'">
                <h1 class="text-3xl font-extrabold text-indigo-900 leading-tight">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-500">
                        Inscriptions en attente
                    </span>
                </h1>
                <p class="mt-1 text-gray-500 text-sm">Gérez les demandes d'inscription à votre plateforme</p>
            </div>
            <a href="{{ route('admin.users.index') }}" 
               class="mt-4 md:mt-0 transform transition-all duration-500 hover:scale-105 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               :class="showHeader ? 'translate-x-0 opacity-100' : 'translate-x-8 opacity-0'">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
        </div>
        
        <!-- Alert Section with Animation -->
        @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-4"
             class="mb-6 rounded-lg bg-green-50 p-4 border-l-4 border-green-400 shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800 font-medium">{{ session('success') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="show = false" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <span class="sr-only">Fermer</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Content Card with Animation -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden transition-all duration-500 transform hover:shadow-2xl"
             x-data="{ 
                 showCard: false,
                 pendingCount: {{ $pendingUsers->count() }},
                 sortBy: 'created_at',
                 sortDesc: true,
                 search: '',
                 perPage: 10,
                 currentPage: 1,
                 get filteredUsers() {
                     // Note: This is a placeholder for Alpine.js logic
                     // Actual filtering happens server-side in this Laravel app
                     return [];
                 }
             }"
             x-init="setTimeout(() => showCard = true, 300)">
            <div class="border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50"
                 :class="showCard ? 'opacity-100' : 'opacity-0'">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="p-2 rounded-full bg-indigo-100 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">
                            Demandes d'inscription à examiner
                            @if($pendingUsers->count() > 0)
                            <span class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                {{ $pendingUsers->count() }}
                            </span>
                            @endif
                        </h2>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                            <div class="py-1">
                                <a href="#" @click="perPage = 10; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                    Afficher 10 par page
                                </a>
                                <a href="#" @click="perPage = 25; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                    Afficher 25 par page
                                </a>
                                <a href="#" @click="perPage = 50; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                    Afficher 50 par page
                                </a>
                                <a href="#" @click="perPage = 100; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                    Afficher 100 par page
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Search Bar -->
                @if($pendingUsers->count() > 0)
                <div class="px-6 py-3 bg-white border-b border-gray-200">
                    <div class="relative rounded-md shadow-sm max-w-lg mx-auto">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" 
                               x-model="search" 
                               class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 py-3 sm:text-sm border-gray-200 rounded-md" 
                               placeholder="Rechercher par nom, email ou téléphone...">
                        <div class="absolute inset-y-0 right-0 flex items-center">
                            <div>
                                <button @click="search = ''" class="h-full py-0 pl-2 pr-3 text-gray-500 hover:text-gray-700">
                                    <span class="sr-only">Effacer</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Table Section -->
            <div class="p-6" :class="showCard ? 'opacity-100' : 'opacity-0'">
                @if($pendingUsers->count() > 0)
                <div class="overflow-x-auto">
                    <table id="pendingTable" class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th scope="col" @click="sortBy = 'nom'; sortDesc = !sortDesc" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-50 group">
                                    <div class="flex items-center">
                                        Nom
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path x-show="sortBy !== 'nom'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                            <path x-show="sortBy === 'nom' && sortDesc" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            <path x-show="sortBy === 'nom' && !sortDesc" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" @click="sortBy = 'email'; sortDesc = !sortDesc" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-50 group">
                                    <div class="flex items-center">
                                        Email
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path x-show="sortBy !== 'email'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                            <path x-show="sortBy === 'email' && sortDesc" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            <path x-show="sortBy === 'email' && !sortDesc" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Téléphone
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type de compte
                                </th>
                                <th scope="col" @click="sortBy = 'created_at'; sortDesc = !sortDesc" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-50 group">
                                    <div class="flex items-center">
                                        Date d'inscription
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path x-show="sortBy !== 'created_at'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                            <path x-show="sortBy === 'created_at' && sortDesc" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            <path x-show="sortBy === 'created_at' && !sortDesc" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingUsers as $user)
                            <tr class="transition-all hover:bg-indigo-50"
                                x-data="{ showActions: false, approveConfirm: false, rejectConfirm: false }"
                                @mouseenter="showActions = true"
                                @mouseleave="showActions = false">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-bold uppercase text-sm">
                                            {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->prenom }} {{ $user->nom }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="mailto:{{ $user->email }}" class="text-sm text-indigo-600 hover:text-indigo-900 hover:underline">
                                        {{ $user->email }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a href="tel:{{ $user->telephone }}" class="hover:text-indigo-600 hover:underline">
                                        {{ $user->telephone }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $user->role->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span title="{{ $user->created_at->format('d/m/Y H:i:s') }}" class="group relative cursor-pointer">
                                        {{ $user->created_at->format('d/m/Y H:i') }}
                                        <small class="text-xs text-gray-400"> ({{ $user->created_at->diffForHumans() }})</small>
                                        
                                        <!-- Tooltip -->
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-xs bg-black text-white rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-10">
                                            Inscription exacte : {{ $user->created_at->format('d/m/Y H:i:s') }}
                                            <svg class="absolute top-full left-1/2 transform -translate-x-1/2 text-black h-2 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 12">
                                                <path fill="currentColor" d="M12 12L0 0h24z" />
                                            </svg>
                                        </span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center"
                                    x-data="{ open: false }">
                                    <div class="inline-flex items-center justify-center">
                                        <a href="{{ route('admin.users.review', $user->id) }}" 
                                           class="mr-2 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform duration-200 hover:scale-105">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Examiner
                                        </a>
                                        
                                        <div class="relative" @click.away="open = false">
                                            <button @click="open = !open" 
                                                    class="inline-flex items-center px-2 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                                </svg>
                                            </button>
                                            
                                            <!-- Dropdown menu -->
                                            <div x-show="open" 
                                                 x-transition:enter="transition ease-out duration-100" 
                                                 x-transition:enter-start="transform opacity-0 scale-95" 
                                                 x-transition:enter-end="transform opacity-100 scale-100" 
                                                 x-transition:leave="transition ease-in duration-75" 
                                                 x-transition:leave-start="transform opacity-100 scale-100" 
                                                 x-transition:leave-end="transform opacity-0 scale-95" 
                                                 class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                                <div class="py-1">
                                                    <button @click="approveConfirm = true; open = false" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 w-full text-left">
                                                        <svg class="mr-3 h-5 w-5 text-green-500 group-hover:text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Approuver
                                                    </button>
                                                    <button @click="rejectConfirm = true; open = false" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 w-full text-left">
                                                        <svg class="mr-3 h-5 w-5 text-red-500 group-hover:text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Rejeter
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Approve Confirmation Modal -->
                                            <div x-show="approveConfirm" 
                                                 class="fixed inset-0 z-50 overflow-y-auto" 
                                                 style="display: none;">
                                                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                                    </div>
                                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <div class="sm:flex sm:items-start">
                                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                </div>
                                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                                        Approuver l'inscription
                                                                    </h3>
                                                                    <div class="mt-2">
                                                                        <p class="text-sm text-gray-500">
                                                                            Voulez-vous vraiment approuver l'inscription de <strong>{{ $user->prenom }} {{ $user->nom }}</strong> ? Un e-mail sera envoyé pour l'informer.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <form action="{{ route('admin.users.approve', $user->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                                    Confirmer
                                                                </button>
                                                            </form>
                                                            <button @click="approveConfirm = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                                    Annuler
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Reject Confirmation Modal -->
                                            <div x-show="rejectConfirm" 
                                                 class="fixed inset-0 z-50 overflow-y-auto" 
                                                 style="display: none;">
                                                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                                    </div>
                                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <div class="sm:flex sm:items-start">
                                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </div>
                                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                                        Rejeter l'inscription
                                                                    </h3>
                                                                    <div class="mt-2">
                                                                        <p class="text-sm text-gray-500">
                                                                            Voulez-vous vraiment rejeter l'inscription de <strong>{{ $user->prenom }} {{ $user->nom }}</strong> ? Un e-mail sera envoyé pour l'informer.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <form action="{{ route('admin.users.reject', $user->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                                    Confirmer
                                                                </button>
                                                            </form>
                                                            <button @click="rejectConfirm = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                                Annuler
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Section -->
                <div class="mt-6">
                    {{ $pendingUsers->links() }}
                </div>
                
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune inscription en attente</h3>
                    <p class="mt-1 text-sm text-gray-500">Il n'y a actuellement aucune demande d'inscription à examiner.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Additional Alpine.js functionality can be added here
    document.addEventListener('alpine:init', () => {
        // This is a placeholder for additional functionality
        // Actual implementation would depend on specific requirements
    });
</script>
@endsection