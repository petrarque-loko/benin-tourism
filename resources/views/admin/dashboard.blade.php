<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tableau de bord - Administration Tourisme Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
        }
        
        #sidebar {
            width: 280px;
            background-color: #2c3e50;
            color: white;
            transition: all 0.3s;
        }
        
        #sidebar .sidebar-header {
            padding: 20px;  
            background-color: #1a252f;
        }
        
        #sidebar ul.components {
            padding: 20px 0;
        }
        
        #sidebar ul li a {
            padding: 10px 20px;
            font-size: 1.1em;
            display: block;
            color: white;
            text-decoration: none;
        }
        
        #sidebar ul li a:hover {
            background-color: #3e5871;
        }
        
        #sidebar ul li.active > a {
            background-color: #3e5871;
        }
        
        #content {
            width: calc(100% - 280px);
            min-height: 100vh;
            transition: all 0.3s;
            background-color: #f8f9fa;
            background-image: url('/images/background.jpg');
            background-size: cover;
            

        }
        
        .card-dashboard {
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 20px;
        }
        
        .card-stats {
            border-left: 4px solid;
        }
        
        .card-stats-blue {
            border-left-color: #4e73df;
        }
        
        .card-stats-green {
            border-left-color: #1cc88a;
        }
        
        .card-stats-orange {
            border-left-color: #f6c23e;
        }
        
        .card-stats-red {
            border-left-color: #e74a3b;
        }
        
        .icon-shape {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* Styles pour les notifications */
        .notification-counter {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #e74a3b;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .notification-icon {
            position: relative;
            font-size: 1.4rem;
            color: #6c757d;
            cursor: pointer;
        }

        .notification-dropdown {
            min-width: 300px;
            max-height: 400px;
            overflow-y: auto;
            padding: 0;
        }

        .notification-dropdown .notification-item {
            display: flex;
            padding: 10px 15px;
            border-bottom: 1px solid #e3e6f0;
            text-decoration: none;
            color: #3a3b45;
        }

        .notification-dropdown .notification-item:hover {
            background-color: #f8f9fc;
        }

        .notification-dropdown .notification-item .notification-icon {
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .notification-dropdown .notification-item .notification-content {
            flex: 1;
        }

        .notification-dropdown .notification-item .notification-time {
            font-size: 0.8rem;
            color: #858796;
        }

        .notification-dropdown .mark-all-read {
            text-align: center;
            padding: 10px;
            background-color: #f8f9fc;
            color: #4e73df;
            cursor: pointer;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <div class="sidebar-header">
            <h3>Tourisme Bénin</h3>
        </div>
        
        <ul class="list-unstyled components">
            <li class="active">
                <a href="dashboard.html"><i class="bi bi-speedometer2 me-2"></i> Tableau de bord</a>
            </li>
            <li>
                <a href="sites.html"><i class="bi bi-geo-alt me-2"></i> Sites touristiques</a>
            </li>
            <li>
                <a href="hebergements.html"><i class="bi bi-building me-2"></i> Hébergements</a>
            </li>
            <li>
                <a href="circuits.html"><i class="bi bi-map me-2"></i> Circuits</a>
            </li>
            <li>
                <a href="evenements.html"><i class="bi bi-calendar-event me-2"></i> Événements</a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> Utilisateurs</a>
            </li>
            <li>
                <a href="articles.html"><i class="bi bi-newspaper me-2"></i> Articles</a>
            </li>
            <li>
                <a href="parametres.html"><i class="bi bi-gear me-2"></i> Paramètres</a>
            </li>
            <li>
                <form method="POST" action="/admin/logout" id="logout-form">
                    <input type="hidden" name="_token" value="CSRF_TOKEN_HERE">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                    </a>
                </form>
            </li>
        </ul>
    </div>
    
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 px-4 shadow-sm">
            <div class="container-fluid">
                <h4 class="mb-0">Tableau de bord</h4>
                <div class="ms-auto d-flex align-items-center">
                    <!-- Cloche de notification -->
                    <div class="dropdown me-3">
                        <a class="notification-icon dropdown-toggle" href="#" role="button" id="notificationDropdownMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            <span class="notification-counter">0</span>
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown" id="notificationsDropdown" aria-labelledby="notificationDropdownMenu">
                            <!-- Les notifications seront chargées ici dynamiquement -->
                            <li><span class="dropdown-item text-center">Chargement des notifications...</span></li>
                        </ul>
                    </div>
                    
                    <!-- Dropdown utilisateur -->
                    <div class="dropdown">
                        <a class="btn dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> Admin User
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Paramètres</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <div class="container-fluid px-4 py-4">
            <h1 class="h3 mb-4 text-gray-800">Tableau de bord</h1>
            
            <!-- Première rangée - Statistiques générales -->
            <div class="row">
                <!-- Carte Utilisateurs totaux -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-dashboard card-stats card-stats-blue">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Utilisateurs totaux</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $totalUsers }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-primary text-white rounded-circle p-3">
                                        <i class="bi bi-people"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Sites touristiques -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-dashboard card-stats card-stats-blue">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Touristes</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $touristesCount }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle p-3">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Hébergements -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-dashboard card-stats card-stats-green">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Guides</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $guidesCount }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle p-3">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Réservations -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-dashboard card-stats card-stats-red">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Propriétaires</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $proprietairesCount }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle p-3">
                                        <i class="bi bi-building"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Deuxième rangée - Activités et Sites populaires -->
            <div class="row">
                <!-- Activités récentes -->
                <div class="col-lg-8">
                    <div class="card card-dashboard shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Activités récentes</h6>
                            <a href="activites.html" class="btn btn-sm btn-primary">
                                Voir tout
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Utilisateur</th>
                                            <th>Action</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Jean Dupont</td>
                                            <td>A réservé un circuit à Ganvié</td>
                                            <td>25/02/2025</td>
                                        </tr>
                                        <tr>
                                            <td>Marie Koné</td>
                                            <td>A publié un commentaire sur Ouidah</td>
                                            <td>24/02/2025</td>
                                        </tr>
                                        <tr>
                                            <td>Pierre Adeyemi</td>
                                            <td>A réservé un hébergement à Cotonou</td>
                                            <td>23/02/2025</td>
                                        </tr>
                                        <tr>
                                            <td>Sophie Amoussou</td>
                                            <td>S'est inscrite sur la plateforme</td>
                                            <td>22/02/2025</td>
                                        </tr>
                                        <tr>
                                            <td>Thomas Sossou</td>
                                            <td>A noté 5 étoiles pour le Parc Pendjari</td>
                                            <td>22/02/2025</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sites populaires -->
                <div class="col-lg-4">
                    <div class="card card-dashboard shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Sites populaires</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Parc National de la Pendjari</span>
                                    <span>85%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Porte du Non-Retour (Ouidah)</span>
                                    <span>78%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Ganvié (Village lacustre)</span>
                                    <span>66%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Palais Royal d'Abomey</span>
                                    <span>58%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 58%" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Plage de Grand-Popo</span>
                                    <span>45%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Troisième rangée - Inscriptions en attente et Graphique -->
            <div class="row">
                <!-- Inscriptions en attente -->
                <div class="col-lg-6">
                    <div class="card card-dashboard shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Inscriptions en attente</h6>
                            <a href="{{ route('admin.users.pending') }}" class="btn btn-sm btn-primary">
                                Voir tout
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentPendingUsers as $user)
                                        <tr>
                                            <td>{{ $user->prenom }} {{ $user->nom }}</td>
                                            <td>{{ $user->role->name }}</td>
                                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.review', $user->id) }}" class="btn btn-info btn-sm">
                                                    <i class="bi bi-search"></i> Examiner
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Aucun utilisateur en attente</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Répartition des utilisateurs -->
                <div class="col-lg-6">
                    <div class="card card-dashboard shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Répartition des utilisateurs</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="usersPieChart"></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                <span class="mr-2">
                                    <i class="bi bi-circle-fill text-primary"></i> Touristes
                                </span>
                                <span class="mx-2">
                                    <i class="bi bi-circle-fill text-success"></i> Guides
                                </span>
                                <span class="ml-2">
                                    <i class="bi bi-circle-fill text-info"></i> Propriétaires
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Définir la locale française pour moment.js
        moment.locale('fr');
        
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique de répartition des utilisateurs
            var ctx = document.getElementById("usersPieChart").getContext('2d');
            var myPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ["Touristes", "Guides touristiques", "Propriétaires d'hébergement"],
                    datasets: [{
                        data: [{{ $touristesCount }}, {{ $guidesCount }}, {{ $proprietairesCount }}],
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutout: '80%',
                }
            });
        });
    </script>
    
    <!-- Script pour les notifications -->
    <script>

$(document).ready(function() {
    // Fonction pour charger les notifications
    function loadNotifications() {
        $.ajax({
            url: '/admin/notifications',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Mettre à jour le compteur
                $('.notification-counter').text(data.unread_count);
                
                // Cacher le compteur s'il n'y a pas de notifications non lues
                if (data.unread_count === 0) {
                    $('.notification-counter').hide();
                } else {
                    $('.notification-counter').show();
                }
                
                // Vider le conteneur
                $('#notificationsDropdown').empty();
                
                // Aucune notification
                if (data.notifications.length === 0) {
                    $('#notificationsDropdown').append(
                        '<li><span class="dropdown-item text-center">Aucune notification</span></li>'
                    );
                    return;
                }
                
                // Ajouter les notifications
                $.each(data.notifications, function(index, notification) {
                    var notifItem = $('<a></a>')
                        .addClass('notification-item')
                        .attr('href', (notification.type === 'registration' ?
                               '/admin/registrations/' + notification.notifiable_id + '/review' : '#'));
                    
                    var iconDiv = $('<div></div>').addClass('notification-icon');
                    var iconColor = notification.read ? 'text-gray-500' : 'text-primary';
                    
                    if (notification.type === 'registration') {
                        iconDiv.append('<i class="bi bi-person-plus ' + iconColor + '"></i>');
                    } else if (notification.type === 'reservation') {
                        iconDiv.append('<i class="bi bi-calendar-check ' + iconColor + '"></i>');
                    } else if (notification.type === 'comment') {
                        iconDiv.append('<i class="bi bi-chat-dots ' + iconColor + '"></i>');
                    } else {
                        iconDiv.append('<i class="bi bi-bell ' + iconColor + '"></i>');
                    }
                    
                    var contentDiv = $('<div></div>').addClass('notification-content');
                    
                    var messageDiv = $('<div></div>')
                        .addClass(notification.read ? '' : 'fw-bold')
                        .text(notification.message);
                    
                    var textDiv = $('<div></div>')
                        .addClass('notification-time')
                        .text(moment(notification.created_at).fromNow());
                    
                    contentDiv.append(messageDiv, textDiv);
                    notifItem.append(iconDiv, contentDiv);
                    
                    $('#notificationsDropdown').append($('<li></li>').append(notifItem));
                });
                
                // Ajouter le lien "Marquer comme lu"
                if (data.unread_count > 0) {
                    $('#notificationsDropdown').append('<li><hr class="dropdown-divider"></li>');
                    $('#notificationsDropdown').append(
                        '<li><div class="mark-all-read" id="markAllRead">Marquer toutes comme lues</div></li>'
                    );
                }
                
                // Ajouter le lien "Voir toutes les notifications"
                $('#notificationsDropdown').append('<li><hr class="dropdown-divider"></li>');
                $('#notificationsDropdown').append(
                    '<li><a class="dropdown-item text-center" href="/admin/notifications/all">Voir toutes les notifications</a></li>'
                );
            },
            error: function() {
                $('#notificationsDropdown').empty().append(
                    '<li><span class="dropdown-item text-center">Erreur de chargement</span></li>'
                );
                
                // Afficher un message d'erreur
                toastr.error('Impossible de charger les notifications. Veuillez réessayer plus tard.');
            }
        });
    }
    
    // Charger les notifications au chargement
    loadNotifications();
    
    // Rafraîchir toutes les 30 secondes
    setInterval(loadNotifications, 30000);
    
    // Gérer le clic sur "Marquer comme lu"
    $(document).on('click', '#markAllRead', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '/admin/notifications/read',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                // Recharger les notifications
                loadNotifications();
                
                // Afficher un message de succès
                toastr.success('Toutes les notifications ont été marquées comme lues.');
            },
            error: function() {
                // Afficher un message d'erreur
                toastr.error('Une erreur est survenue. Veuillez réessayer plus tard.');
            }
        });
    });
    
    // Gérer le clic sur une notification
    $(document).on('click', '.notification-item', function() {
        var href = $(this).attr('href');
        
        // Si ce n'est pas un lien vers une autre page (href="#")
        if (href === '#') {
            return false;
        }
        
        // Sinon, laisser la navigation se produire
    });
    
    // Gestion des nouvelles notifications en temps réel (si vous utilisez Pusher ou un système similaire)
    // Note: Cette partie nécessite que vous ayez configuré Pusher ou un autre système de notification en temps réel
    if (typeof Echo !== 'undefined') {
        Echo.private('App.Models.User.' + userID)
            .notification((notification) => {
                // Jouer un son de notification
                var notificationSound = new Audio('/audio/notification.mp3');
                notificationSound.play();
                
                // Afficher une notification toast
                toastr.info(notification.message, 'Nouvelle notification');
                
                // Recharger les notifications
                loadNotifications();
            });
    }
});
    </script>
</body>
</html>