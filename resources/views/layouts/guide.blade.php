<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Guide Touristique Bénin - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.3/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/fr.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <div x-data="{ 
        sidebarOpen: true,
        userMenuOpen: false,
        reservationMenuOpen: false
    }">
        <!-- Sidebar (barre latérale) -->
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar mobile backdrop -->
            <div 
                x-show="sidebarOpen" 
                x-cloak
                class="fixed inset-0 z-20 transition-opacity bg-black bg-opacity-50 lg:hidden"
                @click="sidebarOpen = false">
            </div>

            <!-- Sidebar itself -->
            <div 
                :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
                class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-blue-800 lg:translate-x-0 lg:static lg:inset-0">
                
                <!-- Logo et nom du site -->
                <div class="flex items-center justify-center mt-8">
                    <div class="flex items-center">
                        <span class="mx-2 text-2xl font-semibold text-white"> BéninTourisme</span>
                    </div>
                </div>

                <!-- Menu vertical -->
                <nav class="mt-10">
                    <div class="px-6 py-4 text-white border-b border-blue-700">
                        <p class="text-xs text-blue-400">GESTION DES CIRCUITS</p>
                    </div>
                    
                    <a class="flex items-center px-6 py-3 mt-2 text-white hover:bg-blue-700" href=" {{ route('guide.dashboard') }} ">
                        <i class="mr-3 fas fa-tachometer-alt"></i>
                        <span>Tableau de bord</span>
                    </a>
                    
                    <a class="flex items-center px-6 py-3 text-white hover:bg-blue-700" href="#">
                        <i class="mr-3 fas fa-route"></i>
                        <span>Mes circuits touristiques</span>
                    </a>

                    <a class="flex items-center px-6 py-3 text-white hover:bg-blue-700" href="{{ route('guide.reservations') }}">
                        <i class="mr-3 fas fa-calendar-check"></i>
                        <span>Mes Réservations</span>
                    </a>
                    
                    <!-- Menu déroulant pour les réservations -->
                    <!-- <div x-data="{ open: false }">
                        <button 
                            @click="open = !open" 
                            class="flex items-center justify-between w-full px-6 py-3 text-white hover:bg-blue-700">
                            <div class="flex items-center">
                                <i class="mr-3 fas fa-calendar-check"></i>
                                <span>Mes réservations</span>
                            </div>
                            <i :class="open ? 'fa-chevron-down' : 'fa-chevron-right'" class="fas text-xs"></i>
                        </button>
                        
                        <div x-show="open" x-cloak class="bg-blue-900">
                            <a class="flex items-center px-6 py-3 pl-12 text-white hover:bg-blue-700" href="{{ route('guide.reservations') }}">
                                <i class="mr-3 fas fa-map-marked-alt"></i>
                                <span>Réservations de circuits</span>
                            </a>
                            <a class="flex items-center px-6 py-3 pl-12 text-white hover:bg-blue-700" href="{{ route('guide.reservations') }}">
                                <i class="mr-3 fas fa-landmark"></i>
                                <span>Réservations de sites</span>
                            </a>
                        </div>
                    </div> -->
                    
                    <div class="px-6 py-4 text-white border-b border-blue-700 mt-6">
                        <p class="text-xs text-blue-400">PARAMÈTRES</p>
                    </div>
                    
                    <a class="flex items-center px-6 py-3 text-white hover:bg-blue-700" href="{{ route('profile.show') }}">
                        <i class="mr-3 fas fa-user-cog"></i>
                        <span>Mon profil</span>
                    </a>
                    
                    <a class="flex items-center px-6 py-3 text-white hover:bg-blue-700" href="">
                        <i class="mr-3 fas fa-chart-bar"></i>
                        <span>Statistiques</span>
                    </a>
                    
                    <a class="flex items-center px-6 py-3 text-white hover:bg-blue-700" href="{{ route('logout') }}">
                        <i class="mr-3 fas fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </a>
                </nav>
            </div>

            <!-- Main content area -->
            <div class="flex flex-col flex-1 overflow-hidden">
                
                <!-- Top navigation bar -->
                <header class="flex items-center justify-between px-6 py-4 bg-white border-b">
                <h1 class="text-xl font-medium bg-clip-text text-transparent" style="background-image: linear-gradient(135deg, #1F2937, #4B5563);">{{ $pageTitle ?? 'GUIDE TOURISTIQUE' }}</h1>

                    <!-- Toggle sidebar button -->
                    <div class="flex items-center">
                        <button 
                            @click="sidebarOpen = !sidebarOpen" 
                            class="text-gray-500 focus:outline-none lg:hidden">
                            <i class="fas fa-bars"></i>
                        </button>
                        <!-- Page title (will be replaced by yield) -->
                        <h2 class="ml-4 text-2xl font-medium">@yield('title')</h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notification dropdown -->
                        <div 
                            x-data="notificationsManager()" 
                            @click.away="isOpen = false"
                            class="relative">
                            <button 
                                @click="toggleDropdown(); fetchNotifications()"
                                class="p-1 text-gray-600 transition-colors duration-300 rounded-full hover:text-blue-600 hover:bg-blue-100 focus:outline-none">
                                <div class="relative">
                                    <i class="text-xl fas fa-bell"></i>
                                    <span 
                                        x-show="unreadCount > 0" 
                                        x-text="unreadCount"
                                        x-cloak
                                        class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-xs text-white bg-red-500 rounded-full">
                                    </span>
                                </div>
                            </button>

                            <!-- Notifications panel -->
                            <div 
                                x-show="isOpen" 
                                x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 z-50 w-80 mt-2 overflow-hidden bg-white rounded-md shadow-lg">
                                <div class="py-2 border-b">
                                    <div class="flex items-center justify-between px-4">
                                        <h3 class="text-sm font-semibold text-gray-700">Notifications</h3>
                                        <template x-if="unreadCount > 0">
                                            <button 
                                                @click="markAllAsRead"
                                                class="text-xs text-blue-500 hover:underline">Marquer tout comme lu</button>
                                        </template>
                                    </div>
                                </div>
                                
                                <div class="max-h-64 overflow-y-auto divide-y divide-gray-100">
                                    <template x-if="loading">
                                        <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                            Chargement...
                                        </div>
                                    </template>
                                    
                                    <template x-if="!loading && notifications.length === 0">
                                        <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                            Aucune notification
                                        </div>
                                    </template>
                                    
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <a 
                                            :href="getNotificationUrl(notification)"
                                            class="block transition-colors duration-300 hover:bg-gray-100">
                                            <div 
                                                :class="{'bg-blue-50': !notification.read_at}"
                                                class="flex items-center px-4 py-3">
                                                <div class="flex-shrink-0 mr-3">
                                                    <i :class="getNotificationIcon(notification)" class="p-2 text-white bg-blue-700 rounded-full"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <p 
                                                        :class="{'font-semibold': !notification.read_at}"
                                                        class="text-sm text-gray-700">
                                                        <span x-text="notification.data.message"></span>
                                                    </p>
                                                    <p class="text-xs text-gray-500" x-text="formatDate(notification.created_at)"></p>
                                                </div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                                
                                <a href="/notifications" class="block py-2 text-sm font-semibold text-center text-white bg-blue-700 hover:bg-blue-800">
                                    Voir toutes les notifications
                                </a>
                            </div>
                        </div>

                        <!-- Language selector -->
                        <div class="relative" x-data="{ open: false }">
                            <button 
                                @click="open = !open" 
                                class="flex items-center p-1 text-gray-600 transition-colors duration-300 rounded-full hover:text-blue-600 hover:bg-blue-100 focus:outline-none">
                                <i class="text-xl fas fa-globe"></i>
                            </button>

                            <div 
                                x-show="open" 
                                x-cloak
                                @click.away="open = false"
                                class="absolute right-0 z-50 w-48 mt-2 overflow-hidden bg-white rounded-md shadow-lg">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="mr-2 fas fa-flag"></i> Français
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="mr-2 fas fa-flag"></i> English
                                </a>
                            </div>
                        </div>

                        <!-- User dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button 
                                @click="open = !open" 
                                class="flex items-center focus:outline-none">
                                <div class="w-8 h-8 overflow-hidden rounded-full bg-gray-200">
                                    <img src="https://ui-avatars.com/api/?name=Guide&background=EABA0C&color=fff" alt="Avatar">
                                </div>
                                <span class="mx-2 text-sm font-medium text-gray-700">Guide Touristique</span>
                                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                            </button>

                            <!-- User dropdown panel -->
                            <div 
                                x-show="open" 
                                x-cloak
                                @click.away="open = false"
                                class="absolute right-0 z-50 w-48 mt-2 overflow-hidden bg-white rounded-md shadow-lg">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="mr-2 fas fa-user"></i> Mon Profil
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="mr-2 fas fa-cog"></i> Paramètres
                                </a>
                                <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="mr-2 fas fa-sign-out-alt"></i> Déconnexion
                                </a>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                    <!-- The content section - to be extended by specific pages -->
                    <div class="container mx-auto">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        function notificationsManager() {
            return {
                isOpen: false,
                loading: true,
                notifications: [],
                unreadCount: 0,
                
                // Initialisation au chargement de la page
                init() {
                    this.fetchNotificationsCount();
                    
                    // Actualiser le compteur toutes les 30 secondes
                    setInterval(() => {
                        this.fetchNotificationsCount();
                    }, 30000);
                },
                
                // Ouvrir/fermer le dropdown
                toggleDropdown() {
                    this.isOpen = !this.isOpen;
                },
                
                // Récupérer les notifications
                fetchNotifications() {
                    this.loading = true;
                    
                    fetch('/notifications/guide')
                        .then(response => response.json())
                        .then(data => {
                            this.notifications = data.notifications;
                            this.unreadCount = data.unread_count;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Erreur lors du chargement des notifications:', error);
                            this.loading = false;
                        });
                },
                
                // Récupérer uniquement le compteur
                fetchNotificationsCount() {
                    fetch('/notifications/guide/count')
                        .then(response => response.json())
                        .then(data => {
                            this.unreadCount = data.unread_count;
                        })
                        .catch(error => {
                            console.error('Erreur lors du chargement du compteur:', error);
                        });
                },
                
                // Marquer toutes les notifications comme lues
                markAllAsRead() {
                    fetch('/notifications/guide/read-all', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.notifications.forEach(notification => {
                                notification.read_at = new Date().toISOString();
                            });
                            this.unreadCount = 0;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la lecture des notifications:', error);
                    });
                },
                
                // Formater la date
                formatDate(dateString) {
                    return moment(dateString).locale('fr').fromNow();
                },
                
                // Obtenir l'URL associée à une notification
                getNotificationUrl(notification) {
                    if (notification.data.url) {
                        return notification.data.url;
                    }
                    
                    // Notifications liées aux réservations de circuits
                    if (['circuit.reservation.new', 'circuit.reservation.modified', 'circuit.reservation.cancelled'].includes(notification.data.type)) {
                        return `/guide/reservations/circuits/${notification.data.reservation_id}`;
                    }
                    
                    // Notifications liées aux réservations de sites
                    if (['site.reservation.new', 'site.reservation.modified', 'site.reservation.cancelled'].includes(notification.data.type)) {
                        return `/guide/reservations/sites/${notification.data.reservation_id}`;
                    }
                    
                    // Notifications liées aux commentaires
                    if (notification.data.type === 'comment.new') {
                        return `/guide/circuits/${notification.data.circuit_id}#comments`;
                    }
                    
                    // Si le type n'est pas reconnu mais qu'on a une ID de circuit
                    if (notification.data.circuit_id) {
                        return `/guide/circuits/${notification.data.circuit_id}`;
                    }
                    
                    // Si le type n'est pas reconnu mais qu'on a une ID de site
                    if (notification.data.site_id) {
                        return `/guide/sites/${notification.data.site_id}`;
                    }
                    
                    // URL par défaut
                    return '#';
                },
                
                // Obtenir l'icône associée à une notification
                getNotificationIcon(notification) {
                    const type = notification.data.type;
                    const baseClass = "fas ";
                    
                    // Notifications de réservation de circuit
                    if (type === 'circuit.reservation.new') {
                        return `${baseClass} fa-route`;
                    } else if (type === 'circuit.reservation.modified') {
                        return `${baseClass} fa-map-marked-alt`;
                    } else if (type === 'circuit.reservation.cancelled') {
                        return `${baseClass} fa-ban`;
                    }
                    
                    // Notifications de réservation de site
                    else if (type === 'site.reservation.new') {
                        return `${baseClass} fa-landmark`;
                    } else if (type === 'site.reservation.modified') {
                        return `${baseClass} fa-map-pin`;
                    } else if (type === 'site.reservation.cancelled') {
                        return `${baseClass} fa-ban`;
                    }
                    
                    // Notifications de commentaire
                    else if (type === 'comment.new') {
                        return `${baseClass} fa-comment`;
                    }
                    
                    // Types par défaut
                    else if (type && type.includes('circuit')) {
                        return `${baseClass} fa-route`;
                    } else if (type && type.includes('site')) {
                        return `${baseClass} fa-landmark`;
                    } else if (type && type.includes('comment')) {
                        return `${baseClass} fa-comment`;
                    } else {
                        return `${baseClass} fa-bell`;
                    }
                }
            };
        }
    </script>
</body>
</html>