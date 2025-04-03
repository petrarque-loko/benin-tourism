<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Administration Tourisme Bénin' }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Autres dépendances -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/fr.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Animations pour la sidebar et les menus */
        .sidebar-menu-item {
            transition: all 0.3s ease-in-out;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: rgba(255, 255, 255, 0.1);
            transform: skewX(-15deg);
            transition: all 0.4s ease;
            z-index: 0;
        }
        
        .sidebar-menu-item:hover::before {
            width: 100%;
        }
        
        .sidebar-menu-item:hover i {
            transform: translateY(-3px);
            text-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        }
        
        .sidebar-menu-item i {
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .sidebar-menu-item span {
            position: relative;
            z-index: 1;
        }
        
        .menu-active {
            position: relative;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .menu-active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: linear-gradient(to bottom, #3B82F6, #60A5FA);
            border-radius: 2px;
        }
        
        /* Thèmes personnalisables */
        .theme-default {
            --sidebar-bg: #1F2937;
            --sidebar-hover: #374151;
            --sidebar-active: #111827;
            --sidebar-text: #F9FAFB;
            --accent-color: #3B82F6;
        }
        
        .theme-blue {
            --sidebar-bg: #1E3A8A;
            --sidebar-hover: #1E40AF;
            --sidebar-active: #1E3A8A;
            --sidebar-text: #F9FAFB;
            --accent-color: #60A5FA;
        }
        
        .theme-green {
            --sidebar-bg: #065F46;
            --sidebar-hover: #047857;
            --sidebar-active: #064E3B;
            --sidebar-text: #F9FAFB;
            --accent-color: #34D399;
        }
        
        .theme-purple {
            --sidebar-bg: #4B0082;
            --sidebar-hover: #5B21B6;
            --sidebar-active: #3B0764;
            --sidebar-text: #F9FAFB;
            --accent-color: #A78BFA;
        }
        
        .custom-sidebar {
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
        }
        
        .custom-sidebar .active-menu-item {
            background-color: var(--sidebar-active);
        }
        
        .custom-sidebar .sidebar-menu-item:hover {
            background-color: var(--sidebar-hover);
        }
        
        /* Logo animation */
        .logo-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        
        /* 3D Effects */
        .menu-3d {
            transition: all 0.3s ease;
            transform-style: preserve-3d;
            perspective: 1000px;
        }
        
        .menu-3d:hover {
            transform: translateZ(10px) rotateX(2deg);
        }
        
        /* Notification badge animation */
        .notification-badge {
            animation: bounce 1s infinite alternate;
        }
        
        @keyframes bounce {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(1.2);
            }
        }
        
        /* Dropdown animation */
        .dropdown-animation {
            animation: fadeInDown 0.3s ease-out forwards;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800" x-data="{ currentTheme: 'theme-default' }" :class="currentTheme">

<div class="bg-cover bg-center bg-fixed min-h-screen py-" 
     style="background-image: url('/images/background.jpg');">
    <div x-data="siteEditor()">
        <!-- Reste du contenu... -->
    </div>

    <div x-data="{ sidebarOpen: true, notificationOpen: false, themeOpen: false }" class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <div 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 custom-sidebar transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 rounded-r-lg shadow-xl">
            
            <!-- Logo & Titre -->
            <div class="flex items-center justify-between h-16 px-6 bg-opacity-20 bg-black backdrop-blur-sm">
                <div class="flex items-center space-x-2 logo-animation">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #3B82F6, #60A5FA);">
                        <i class="bi bi-geo-alt-fill text-white"></i>
                    </div>
                    <span class="text-xl font-bold bg-clip-text text-transparent" style="background-image: linear-gradient(135deg, #FFFFFF, #A5F3FC);">Tourisme Bénin</span>
                </div>
                <button @click="sidebarOpen = false" class="p-2 rounded-md lg:hidden hover:bg-gray-700 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Menu -->
            <nav class="mt-5 px-2 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item flex items-center px-4 py-3 text-white rounded-md group {{ request()->routeIs('admin.dashboard') ? 'menu-active active-menu-item' : '' }}">
                    <i class="bi bi-speedometer2 mr-3 text-lg"></i>
                    <span class="text-sm">Tableau de bord</span>
                </a>

                <a href="{{ route('admin.sites.index') }}" class="sidebar-menu-item flex items-center px-4 py-3 text-white rounded-md group {{ request()->routeIs('admin.sites.*') ? 'menu-active active-menu-item' : '' }}">
                    <i class="bi bi-geo-alt mr-3 text-lg"></i>
                    <span class="text-sm">Sites touristiques</span>
                </a>

                <a href="{{ route('admin.hebergements.index') }}" class="sidebar-menu-item flex items-center px-4 py-3 text-white rounded-md group {{ request()->routeIs('admin.hebergements.*') ? 'menu-active active-menu-item' : '' }}">
                    <i class="bi bi-building mr-3 text-lg"></i>
                    <span class="text-sm">Hébergements</span>
                </a>

                <!-- Menu déroulant Réservations -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="sidebar-menu-item flex items-center justify-between w-full px-4 py-3 text-white rounded-md group">
                        <div class="flex items-center">
                            <i class="bi bi-bookmark mr-3 text-lg"></i>
                            <span class="text-sm">Réservations</span>
                        </div>
                        <i :class="open ? 'bi-chevron-up' : 'bi-chevron-down'" class="bi text-sm"></i>
                    </button>

                    <div x-show="open" class="ml-6 mt-2 space-y-1">
                        <a href="{{ route('admin.sites.reservations') }}" class="flex items-center px-4 py-2 text-white rounded-md hover:bg-blue-600">
                            <i class="bi bi-map text-sm mr-2"></i>
                            <span class="text-sm">Réservation de sites</span>
                        </a>
                        <a href="{{ route('admin.hebergements.reservations') }}" class="flex items-center px-4 py-2 text-white rounded-md hover:bg-blue-600">
                            <i class="bi bi-house text-sm mr-2"></i>
                            <span class="text-sm">Réservations d'hébergements</span>
                        </a>
                        <a href="" class="flex items-center px-4 py-2 text-white rounded-md hover:bg-blue-600">
                            <i class="bi bi-geo-alt text-sm mr-2"></i>
                            <span class="text-sm">Réservations de circuits</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.users.index') }}" class="sidebar-menu-item flex items-center px-4 py-3 text-white rounded-md group {{ request()->routeIs('admin.users.*') ? 'menu-active active-menu-item' : '' }}">
                    <i class="bi bi-people mr-3 text-lg"></i>
                    <span class="text-sm">Utilisateurs</span>
                </a>

                <a href="" class="sidebar-menu-item flex items-center px-4 py-3 text-white rounded-md group {{ request()->routeIs('admin.articles.*') ? 'menu-active active-menu-item' : '' }}">
                    <i class="bi bi-newspaper mr-3 text-lg"></i>
                    <span class="text-sm">Articles</span>
                </a>

                <a href="" class="sidebar-menu-item flex items-center px-4 py-3 text-white rounded-md group {{ request()->routeIs('admin.parametres.*') ? 'menu-active active-menu-item' : '' }}">
                    <i class="bi bi-gear mr-3 text-lg"></i>
                    <span class="text-sm">Paramètres</span>
                </a>

                <div class="pt-5 mt-5 border-t border-gray-700">
                    <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="sidebar-menu-item w-full flex items-center px-4 py-3 text-white rounded-md group">
                            <i class="bi bi-box-arrow-right mr-3 text-lg"></i>
                            <span class="text-sm">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </nav>

            
            <!-- Sélecteur de thème -->
            <div class="absolute bottom-4 left-0 right-0 px-4">
                <div @click="themeOpen = !themeOpen" class="flex items-center justify-center py-2 px-4 bg-gray-800 bg-opacity-50 rounded-md cursor-pointer hover:bg-opacity-70 transition-all duration-200">
                    <i class="bi bi-palette text-sm mr-2"></i>
                    <span class="text-xs">Thèmes</span>
                    <i class="bi bi-chevron-down text-xs ml-2" :class="themeOpen ? 'transform rotate-180' : ''"></i>
                </div>
                
                <div x-show="themeOpen" x-cloak class="mt-2 p-2 bg-gray-800 bg-opacity-80 backdrop-blur-sm rounded-md dropdown-animation">
                    <div @click="currentTheme = 'theme-default'" class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded cursor-pointer transition-all duration-200">
                        <div class="w-4 h-4 rounded-full bg-gray-700"></div>
                        <span class="text-xs">Défaut</span>
                    </div>
                    <div @click="currentTheme = 'theme-blue'" class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded cursor-pointer transition-all duration-200">
                        <div class="w-4 h-4 rounded-full bg-blue-800"></div>
                        <span class="text-xs">Bleu</span>
                    </div>
                    <div @click="currentTheme = 'theme-green'" class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded cursor-pointer transition-all duration-200">
                        <div class="w-4 h-4 rounded-full bg-green-800"></div>
                        <span class="text-xs">Vert</span>
                    </div>
                    <div @click="currentTheme = 'theme-purple'" class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded cursor-pointer transition-all duration-200">
                        <div class="w-4 h-4 rounded-full bg-purple-800"></div>
                        <span class="text-xs">Violet</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <header class="flex-shrink-0 bg-white shadow-sm">
                <div class="flex items-center justify-between h-16 px-6">
                    <!-- Bouton menu mobile -->
                    <button @click="sidebarOpen = true" class="p-1 text-gray-500 lg:hidden focus:outline-none hover:text-gray-700 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    
                    <!-- Titre de la page -->
                    <h1 class="text-xl font-medium bg-clip-text text-transparent" style="background-image: linear-gradient(135deg, #1F2937, #4B5563);">{{ $pageTitle ?? 'Tableau de bord' }}</h1>
                    
                    <!-- Droite: Notifications et Profil -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div 
                            x-data="notificationsManager()" 
                            @click.away="isOpen = false"
                            class="relative">
                            <button 
                                @click="toggleDropdown(); fetchNotifications()"
                                class="relative p-1.5 text-gray-600 hover:text-gray-900 focus:outline-none transition-all duration-200 hover:scale-110"
                            >
                                <i class="bi bi-bell text-xl"></i>
                                <span 
                                    x-show="unreadCount > 0" 
                                    x-text="unreadCount"
                                    x-cloak
                                    class="notification-badge absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full"
                                ></span>
                            </button>
                            
                            <!-- Dropdown Notifications -->
                            <div 
                                x-show="isOpen" 
                                x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 z-50 w-80 mt-2 overflow-hidden origin-top-right bg-white rounded-md shadow-xl dropdown-animation"
                            >
                                <div class="py-2">
                                    <div class="px-4 py-2 font-medium text-gray-700 bg-gray-50 border-b">
                                        Notifications
                                    </div>
                                    
                                    <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
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
                                                class="flex px-4 py-3 transition-all duration-200 hover:bg-gray-50"
                                                :class="{'bg-blue-50': !notification.read_at}"
                                            >
                                                <div class="flex-shrink-0 mr-3">
                                                    <i :class="getNotificationIcon(notification)" class="text-lg transition-all duration-200 hover:scale-110"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium" :class="{'text-gray-900': !notification.read_at, 'text-gray-600': notification.read_at}" x-text="notification.data.message"></p>
                                                    <p class="text-xs text-gray-500" x-text="formatDate(notification.created_at)"></p>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                    
                                    <!-- Boutons d'action -->
                                    <template x-if="unreadCount > 0">
                                        <div class="px-4 py-2 bg-gray-50 text-center border-t">
                                            <button 
                                                @click="markAllAsRead"
                                                class="text-sm text-blue-600 hover:text-blue-800 transition-all duration-200 hover:underline"
                                            >
                                                Marquer tout comme lu
                                            </button>
                                        </div>
                                    </template>
                                    
                                    <div class="px-4 py-2 bg-gray-50 text-center border-t">
                                        <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800 transition-all duration-200 hover:underline">
                                            Voir toutes les notifications
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Profil -->
                        <div 
                            x-data="{ isOpen: false }"
                            @click.away="isOpen = false"
                            class="relative"
                        >
                            <button 
                                @click="isOpen = !isOpen"
                                class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none transition-all duration-200"
                            >
                                <i class="bi bi-person-circle text-2xl hover:scale-110 transition-all duration-200"></i>
                                <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                <i class="bi bi-chevron-down text-xs transition-transform duration-200" :class="isOpen ? 'transform rotate-180' : ''"></i>
                            </button>
                            
                            <!-- Dropdown Profil -->
                            <div 
                                x-show="isOpen" 
                                x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 z-50 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg dropdown-animation"
                            >
                                <div class="py-1">
                                    <a href=" {{ route('profile.show') }} " class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-all duration-200">
                                        <i class="bi bi-person mr-2"></i>
                                        Profil
                                    </a>
                                    <a href="" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-all duration-200">
                                        <i class="bi bi-gear mr-2"></i>
                                        Paramètres
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-all duration-200">
                                            <i class="bi bi-box-arrow-right mr-2"></i>
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Contenu principal -->
            <main class="flex-1 overflow-y-auto">
                <div class="container mx-auto px-4 py-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- Scripts pour les notifications -->
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
            
            fetch('/notifications')
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
            fetch('/notifications/count')
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
            fetch('/notifications/read-all', {
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
            
            if (notification.data.type === 'registration') {
                return `/admin/registrations/${notification.data.notifiable_id}/review`;
            }
            
            if (notification.data.type === 'reservation') {
                return `/admin/reservations/${notification.data.reservation_id}`;
            }
            
            if (notification.data.type === 'comment') {
                return `/admin/comments/${notification.data.comment_id}`;
            }
            
            return '#';
        },
        
        // Obtenir l'icône associée à une notification
        getNotificationIcon(notification) {
            const type = notification.data.type;
            const baseClass = notification.read_at 
                ? 'text-gray-400'
                : 'text-blue-500';
            
            if (type === 'registration') {
                return `bi bi-person-plus ${baseClass}`;
            } else if (type === 'reservation') {
                return `bi bi-calendar-check ${baseClass}`;
            } else if (type === 'comment') {
                return `bi bi-chat-dots ${baseClass}`;
            } else {
                return `bi bi-bell ${baseClass}`;
            }
        }
    };
}
    </script>
    
    @stack('scripts')
</body>
</html>