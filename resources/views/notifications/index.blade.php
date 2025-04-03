@php
    $layout = 'layouts.app';
    if (auth()->user()->isAdmin()) {
        $layout = 'layouts.admin';
    } elseif (auth()->user()->isGuide()) {
        $layout = 'layouts.guide';
    } elseif (auth()->user()->isProprietaire()) {
        $layout = 'layouts.proprietaire';
    }
@endphp

@extends($layout)
@section('content')
<div class="container mx-auto px-4 py-8" x-data="notificationsSystem()">
    <!-- En-tête et filtres -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Notifications</h1>
            <p class="text-gray-600">Gérez toutes vos alertes et mises à jour</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <!-- Filtres -->
            <div class="relative">
                <select x-model="filter" class="w-full sm:w-40 bg-white border border-gray-300 rounded-lg py-2 px-3 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all">Toutes</option>
                    <option value="unread">Non lues</option>
                    <option value="read">Lues</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <i class="bi bi-chevron-down text-gray-500"></i>
                </div>
            </div>
            
            <!-- Barre de recherche -->
            <div class="relative flex-grow">
                <input 
                    x-model="searchTerm" 
                    type="text" 
                    placeholder="Rechercher..." 
                    class="w-full bg-white border border-gray-300 rounded-lg py-2 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-search text-gray-500"></i>
                </div>
                <button 
                    x-show="searchTerm" 
                    @click="searchTerm = ''" 
                    class="absolute inset-y-0 right-0 flex items-center pr-3"
                >
                    <i class="bi bi-x-circle text-gray-500 hover:text-gray-700"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="flex justify-between items-center mb-6">
        <div class="text-sm">
            <span x-text="filteredNotifications.length"></span> notification(s) - 
            <span x-text="unreadCount"></span> non lue(s)
        </div>
        
        <div class="flex gap-3">
            <button 
                @click="markAllAsRead" 
                class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                :class="{'opacity-50 cursor-not-allowed': unreadCount === 0}"
                :disabled="unreadCount === 0"
            >
                <i class="bi bi-check-all"></i>
                <span>Tout marquer comme lu</span>
            </button>
            
            <button 
                @click="refreshNotifications" 
                class="flex items-center gap-2 px-4 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors"
            >
                <i class="bi bi-arrow-clockwise" :class="{'animate-spin': isRefreshing}"></i>
                <span>Actualiser</span>
            </button>
        </div>
    </div>
    
    <!-- Liste des notifications -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        <!-- État de chargement -->
        <template x-if="isLoading">
            <div class="flex flex-col items-center justify-center py-12">
                <div class="w-12 h-12 border-4 border-gray-200 border-t-blue-500 rounded-full animate-spin mb-4"></div>
                <p class="text-gray-500">Chargement des notifications...</p>
            </div>
        </template>
        
        <!-- Aucune notification -->
        <template x-if="!isLoading && filteredNotifications.length === 0">
            <div class="flex flex-col items-center justify-center py-16">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-bell-slash text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700 mb-1">Aucune notification</h3>
                <p class="text-gray-500" x-text="filter === 'all' ? 'Vous n\'avez pas encore reçu de notifications.' : 'Aucune notification ne correspond à vos critères.'"></p>
            </div>
        </template>
        
        <!-- Liste des notifications -->
        <template x-if="!isLoading && filteredNotifications.length > 0">
            <div class="divide-y divide-gray-100">
                <template x-for="(notification, index) in paginatedNotifications" :key="notification.id">
                    <div 
                        class="transition-all duration-300 ease-in-out"
                        :class="{'bg-blue-50': !notification.read_at, 'hover:bg-gray-50': notification.read_at}"
                        x-data="{ expanded: false }"
                    >
                        <!-- Notification -->
                        <div 
                            @click="
                                if (notification.data.url) {
                                    if (!notification.read_at) {
                                        markAsRead(notification.id, index);
                                    }
                                    window.location.href = notification.data.url;
                                } else {
                                    expanded = !expanded;
                                }
                            "
                            class="flex items-start gap-4 p-4 cursor-pointer relative group transition-all duration-200"
                            :class="{
                                'bg-blue-50 hover:bg-blue-100': !notification.read_at, 
                                'hover:bg-gray-50': notification.read_at,
                                'border-l-4 border-blue-500': notification.data.url && !notification.read_at,
                                'border-l-4 border-gray-300': notification.data.url && notification.read_at,
                                'border-l-4 border-transparent': !notification.data.url
                            }"
                        >
                            <!-- Indicateur de lien -->
                            <div 
                                x-show="notification.data.url" 
                                class="absolute top-2 right-2 text-gray-400 group-hover:text-blue-500"
                            >
                                <i class="bi bi-box-arrow-up-right text-sm"></i>
                            </div>
                            
                            <!-- Icône -->
                            <div 
                                class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                                :class="getIconBackground(notification)"
                            >
                                <i :class="getIconClass(notification) + ' text-lg'"></i>
                            </div>
                            
                            <!-- Contenu -->
                            <div class="flex-grow min-w-0">
                                <div class="flex justify-between">
                                    <h4 
                                        class="font-medium truncate"
                                        :class="{'text-gray-900': !notification.read_at, 'text-gray-600': notification.read_at}"
                                    >
                                        <span x-text="notification.data.title || getDefaultTitle(notification.data.type)"></span>
                                    </h4>
                                    <span class="text-xs text-gray-500 ml-2 whitespace-nowrap" x-text="formatDate(notification.created_at)"></span>
                                </div>
                                <p 
                                    class="text-sm mt-1"
                                    :class="{'text-gray-800': !notification.read_at, 'text-gray-600': notification.read_at}"
                                    x-text="notification.data.message"
                                ></p>
                                
                                <!-- Badge non lu -->
                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-center gap-2">
                                        <span 
                                            x-show="!notification.read_at"
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800"
                                        >
                                            Non lu
                                        </span>
                                        <span 
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                            :class="getTypeBadgeClass(notification.data.type)"
                                            x-text="getTypeLabel(notification.data.type)"
                                        ></span>
                                    </div>
                                    <span class="text-sm text-blue-600" x-show="notification.data.hasDetails && !notification.data.url">
                                        <span x-show="!expanded">Voir plus <i class="bi bi-chevron-down"></i></span>
                                        <span x-show="expanded">Voir moins <i class="bi bi-chevron-up"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Détails (contenu étendu) -->
                        <div 
                            x-show="expanded" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2"
                            class="px-4 pb-4 pt-1 ml-14 border-t border-gray-100"
                        >
                            <div class="text-sm text-gray-700" x-html="notification.data.details || 'Aucun détail supplémentaire'"></div>
                            
                            <!-- Actions -->
                            <div class="flex justify-end mt-4 gap-2">
                                <template x-if="!notification.read_at">
                                    <button 
                                        @click.stop="markAsRead(notification.id, index)" 
                                        class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm"
                                    >
                                        Marquer comme lu
                                    </button>
                                </template>
                                
                                <template x-if="notification.data.url">
                                    <a 
                                        :href="notification.data.url" 
                                        class="px-3 py-1.5 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors text-sm inline-flex items-center gap-1"
                                    >
                                        Voir <i class="bi bi-arrow-right"></i>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>
    
    <!-- Pagination -->
    <div x-show="totalPages > 1" class="mt-6 flex justify-center">
        <div class="inline-flex items-center rounded-md shadow-sm">
            <button 
                @click="currentPage > 1 ? currentPage-- : null"
                :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"
                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50"
            >
                <i class="bi bi-chevron-left"></i>
            </button>
            
            <template x-for="page in pagesArray" :key="page">
                <button 
                    @click="currentPage = page"
                    :class="{'bg-blue-50 text-blue-600 border-blue-200': page === currentPage, 'hover:bg-gray-50': page !== currentPage}"
                    class="px-3 py-2 text-sm font-medium bg-white border border-gray-300"
                    x-text="page"
                ></button>
            </template>
            
            <button 
                @click="currentPage < totalPages ? currentPage++ : null"
                :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}"
                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50"
            >
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function notificationsSystem() {
        return {
            notifications: [],
            isLoading: true,
            isRefreshing: false,
            filter: 'all',
            searchTerm: '',
            unreadCount: 0,
            currentPage: 1,
            itemsPerPage: 8,
            
            // Initialisation
            init() {
                this.loadNotifications();
                
                // Ajoute une notification toutes les 30 secondes pour la démonstration (à enlever en production)
                if (this.isDemoMode) {
                    this.demoInterval = setInterval(() => {
                        this.addDemoNotification();
                    }, 30000);
                }
            },
            
            get isDemoMode() {
                // Mettre à true pour activer les notifications de démo
                return false;
            },
            
            // Charger les notifications
            loadNotifications() {
                this.isLoading = true;
                
                fetch('/notifications')
                    .then(response => response.json())
                    .then(data => {
                        this.notifications = data.notifications;
                        this.unreadCount = data.unread_count;
                        
                        // Ajouter hasDetails pour la démonstration et URLs pour chaque type de notification
                        this.notifications.forEach(notification => {
                            notification.data.hasDetails = Math.random() > 0.5;
                            
                            // Ajouter des URLs basées sur le type de notification
                            if (!notification.data.url) {
                                switch (notification.data.type) {
                                    case 'registration':
                                        notification.data.url = `/admin/registrations/${notification.data.notifiable_id || 'view'}/review`;
                                        break;
                                    case 'reservation':
                                        notification.data.url = `/reservations/${notification.data.reservation_id || 'view'}`;
                                        break;
                                    case 'comment':
                                        notification.data.url = `/posts/${notification.data.post_id || 'view'}#comment-${notification.data.comment_id || '0'}`;
                                        break;
                                    case 'system':
                                        // Pour les notifications système, on pourrait ne pas avoir d'URL ou rediriger vers les paramètres
                                        if (Math.random() > 0.3) { // Certaines notifications système n'ont pas d'URL
                                            notification.data.url = '/settings/system';
                                        }
                                        break;
                                }
                            }
                            
                            // Simuler des détails s'ils n'existent pas
                            if (!notification.data.details && notification.data.hasDetails) {
                                notification.data.details = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.';
                            }
                        });
                        
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des notifications:', error);
                        this.isLoading = false;
                    });
            },
            
            // Rafraîchir les notifications
            refreshNotifications() {
                this.isRefreshing = true;
                
                fetch('/notifications')
                    .then(response => response.json())
                    .then(data => {
                        this.notifications = data.notifications;
                        this.unreadCount = data.unread_count;
                        
                        // Mise à jour des URLs et détails comme dans loadNotifications
                        this.notifications.forEach(notification => {
                            notification.data.hasDetails = notification.data.hasDetails || Math.random() > 0.5;
                            
                            // Ajouter des URLs basées sur le type de notification
                            if (!notification.data.url) {
                                switch (notification.data.type) {
                                    case 'registration':
                                        notification.data.url = `/admin/users/${notification.data.user_id || 'view'}`;
                                        break;
                                    case 'reservation':
                                        notification.data.url = `/reservations/${notification.data.reservation_id || 'view'}`;
                                        break;
                                    case 'comment':
                                        notification.data.url = `/posts/${notification.data.post_id || 'view'}#comment-${notification.data.comment_id || '0'}`;
                                        break;
                                    case 'system':
                                        if (Math.random() > 0.3) {
                                            notification.data.url = '/settings/system';
                                        }
                                        break;
                                }
                            }
                            
                            if (!notification.data.details && notification.data.hasDetails) {
                                notification.data.details = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
                            }
                        });
                        
                        this.isRefreshing = false;
                    })
                    .catch(error => {
                        console.error('Erreur lors du rafraîchissement des notifications:', error);
                        this.isRefreshing = false;
                    });
            },
            
            // Marquer une notification comme lue
            markAsRead(id, index) {
                fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.notifications[index].read_at = new Date().toISOString();
                        this.unreadCount--;
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du marquage de la notification:', error);
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
                    console.error('Erreur lors du marquage de toutes les notifications:', error);
                });
            },
            
            // Ajouter une notification de démo (pour test)
            addDemoNotification() {
                const types = ['registration', 'reservation', 'comment', 'system'];
                const type = types[Math.floor(Math.random() * types.length)];
                
                const titles = {
                    registration: 'Nouvel utilisateur',
                    reservation: 'Nouvelle réservation',
                    comment: 'Nouveau commentaire',
                    system: 'Mise à jour système'
                };
                
                const messages = {
                    registration: 'Un nouvel utilisateur s\'est inscrit sur la plateforme.',
                    reservation: 'Une nouvelle réservation a été effectuée.',
                    comment: 'Quelqu\'un a commenté sur votre publication.',
                    system: 'Une mise à jour du système est disponible.'
                };
                
                // Générer des IDs fictifs pour les URLs
                const user_id = Math.floor(Math.random() * 1000) + 1;
                const reservation_id = Math.floor(Math.random() * 1000) + 1;
                const post_id = Math.floor(Math.random() * 1000) + 1;
                const comment_id = Math.floor(Math.random() * 1000) + 1;
                
                // Créer les URLs selon le type
                let url;
                switch (type) {
                    case 'registration':
                        url = `/admin/users/${user_id}`;
                        break;
                    case 'reservation':
                        url = `/reservations/${reservation_id}`;
                        break;
                    case 'comment':
                        url = `/posts/${post_id}#comment-${comment_id}`;
                        break;
                    case 'system':
                        url = Math.random() > 0.3 ? '/settings/system' : null;
                        break;
                }
                
                const newNotification = {
                    id: 'demo-' + Date.now(),
                    data: {
                        type: type,
                        title: titles[type],
                        message: messages[type],
                        hasDetails: true,
                        details: 'Ceci est une notification de démonstration générée automatiquement pour tester l\'interface.',
                        url: url,
                        // Ajouter des IDs relatifs selon le type pour les URLs
                        user_id: type === 'registration' ? user_id : null,
                        reservation_id: type === 'reservation' ? reservation_id : null,
                        post_id: type === 'comment' ? post_id : null,
                        comment_id: type === 'comment' ? comment_id : null
                    },
                    read_at: null,
                    created_at: new Date().toISOString()
                };
                
                this.notifications.unshift(newNotification);
                this.unreadCount++;
            },
            
            // Formatage de date
            formatDate(dateString) {
                return moment(dateString).locale('fr').fromNow();
            },
            
            // Obtenir la classe d'icône
            getIconClass(notification) {
                const type = notification.data.type;
                const isRead = notification.read_at !== null;
                const colorClass = isRead ? 'text-gray-400' : 'text-white';
                
                switch (type) {
                    case 'registration':
                        return `bi bi-person-plus ${colorClass}`;
                    case 'reservation':
                        return `bi bi-calendar-check ${colorClass}`;
                    case 'comment':
                        return `bi bi-chat-dots ${colorClass}`;
                    case 'system':
                        return `bi bi-gear ${colorClass}`;
                    default:
                        return `bi bi-bell ${colorClass}`;
                }
            },
            
            // Obtenir la couleur de fond pour l'icône
            getIconBackground(notification) {
                const type = notification.data.type;
                const isRead = notification.read_at !== null;
                
                if (isRead) {
                    return 'bg-gray-200';
                }
                
                switch (type) {
                    case 'registration':
                        return 'bg-green-500';
                    case 'reservation':
                        return 'bg-purple-500';
                    case 'comment':
                        return 'bg-blue-500';
                    case 'system':
                        return 'bg-orange-500';
                    default:
                        return 'bg-gray-500';
                }
            },
            
            // Obtenir le titre par défaut selon le type
            getDefaultTitle(type) {
                switch (type) {
                    case 'registration':
                        return 'Nouvel utilisateur';
                    case 'reservation':
                        return 'Nouvelle réservation';
                    case 'comment':
                        return 'Nouveau commentaire';
                    case 'system':
                        return 'Notification système';
                    default:
                        return 'Notification';
                }
            },
            
            // Obtenir le libellé du type
            getTypeLabel(type) {
                switch (type) {
                    case 'registration':
                        return 'Utilisateur';
                    case 'reservation':
                        return 'Réservation';
                    case 'comment':
                        return 'Commentaire';
                    case 'system':
                        return 'Système';
                    default:
                        return 'Général';
                }
            },
            
            // Obtenir la classe CSS du badge de type
            getTypeBadgeClass(type) {
                switch (type) {
                    case 'registration':
                        return 'bg-green-100 text-green-800';
                    case 'reservation':
                        return 'bg-purple-100 text-purple-800';
                    case 'comment':
                        return 'bg-blue-100 text-blue-800';
                    case 'system':
                        return 'bg-orange-100 text-orange-800';
                    default:
                        return 'bg-gray-100 text-gray-800';
                }
            },
            
            // Filtrer les notifications selon les critères
            get filteredNotifications() {
                return this.notifications.filter(notification => {
                    // Filtre par statut (lu/non lu)
                    if (this.filter === 'read' && !notification.read_at) return false;
                    if (this.filter === 'unread' && notification.read_at) return false;
                    
                    // Filtre par terme de recherche
                    if (this.searchTerm) {
                        const term = this.searchTerm.toLowerCase();
                        const title = (notification.data.title || this.getDefaultTitle(notification.data.type)).toLowerCase();
                        const message = notification.data.message.toLowerCase();
                        const type = this.getTypeLabel(notification.data.type).toLowerCase();
                        
                        return title.includes(term) || message.includes(term) || type.includes(term);
                    }
                    
                    return true;
                });
            },
            
            // Pagination
            get totalPages() {
                return Math.ceil(this.filteredNotifications.length / this.itemsPerPage);
            },
            
            get paginatedNotifications() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredNotifications.slice(start, end);
            },
            
            get pagesArray() {
                const pages = [];
                for (let i = 1; i <= this.totalPages; i++) {
                    pages.push(i);
                }
                return pages;
            },
        };
    }
</script>
@endpush
@endsection