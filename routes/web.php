<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\SiteTouristiqueController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\Guide\GuideController;
use App\Http\Controllers\Cultures\EvenementController;
use App\Http\Controllers\Cultures\TraditionCoutumeController;
use App\Http\Controllers\Touriste\SiteReservationController as SiteTouristeReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChambreCatalogueController;
use App\Http\Controllers\CircuitController;
use App\Http\Controllers\Touriste\CircuitReservationController;
use App\Http\Controllers\Proprietaire\HebergementController as ProprietaireHebergementController;
use App\Http\Controllers\Proprietaire\ChambreController as ProprietaireChambreController;
use App\Http\Controllers\Proprietaire\ReservationController as ProprietaireReservationController;
use App\Http\Controllers\Touriste\HebergementReservationController as TouristeHebergementReservationController;
use App\Http\Controllers\Touriste\PaiementController;
use App\Http\Controllers\Admin\Hebergement\HebergementController as AdminHebergementController;
use App\Http\Controllers\Admin\Hebergement\ChambreController as AdminChambreController;
use App\Http\Controllers\Admin\Hebergement\ReservationController as AdminHebergementReservationController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use Laravel\Telescope\Http\Controllers\HomeController;
use App\Http\Controllers\AccueilController;
// Routes publiques pour l'admin
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
});

// Routes protégées pour l'admin
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['web', 'admin']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    // Autres routes admin ici...
});

// Routes d'authentification
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('register/success', [AuthController::class, 'registerSuccess'])->name('auth.register.success');
Route::get('activate/{token}', [AuthController::class, 'activateAccount'])->name('auth.activate');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Routes de profil
Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.password');
    Route::put('profile/password', [ProfileController::class, 'changePassword'])->name('profile.password.update');
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'getRecentNotifications']);
        Route::get('/count', [NotificationController::class, 'getUnreadCount']);
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::get('/index', [NotificationController::class, 'getAllNotifications'])->name('notifications.index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
    });
    Route::post('circuits/comments/{id}', [CircuitController::class, 'addComment'])->name('circuits.comment');
});

// Réinitialisation de mot de passe
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Routes admin pour la gestion des utilisateurs
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users', [ManageUserController::class, 'index'])->name('users.index');
    Route::get('users/{id}', [ManageUserController::class, 'show'])->name('users.show');
    Route::put('users/{id}/toggle-status', [ManageUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/admin/users/{id}', [ManageUserController::class, 'destroy'])->name('users.destroy');

    
    // Gestion des inscriptions en attente
    Route::get('registrations/pending', [ManageUserController::class, 'pendingRegistrations'])->name('users.pending');
    Route::get('registrations/{id}/review', [ManageUserController::class, 'reviewRegistration'])->name('users.review');
    Route::post('registrations/{id}/approve', [ManageUserController::class, 'approveRegistration'])->name('users.approve');
    Route::post('registrations/{id}/reject', [ManageUserController::class, 'rejectRegistration'])->name('users.reject');
    
    // // Notifications
    // Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    // Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    // Route::post('notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');


 // Sites touristiques
    Route::resource('sites', 'App\Http\Controllers\Admin\SiteTouristiqueController');
    Route::delete('medias/{media}', 'App\Http\Controllers\Admin\SiteTouristiqueController@destroyMedia')->name('medias.destroy');
    Route::patch('commentaires/{commentaire}/toggle-visibility', 'App\Http\Controllers\Admin\SiteTouristiqueController@toggleCommentVisibility')->name('commentaires.toggle-visibility');
    Route::get('reservations/sites', 'App\Http\Controllers\Admin\SiteTouristiqueController@allReservations')->name('sites.reservations');
    Route::get('reservations/sites/{reservation}', 'App\Http\Controllers\Admin\SiteTouristiqueController@showReservation')->name('sites.reservations.show');
    Route::post('reservations/{reservation}/cancel', 'App\Http\Controllers\Admin\SiteTouristiqueController@cancelReservation')->name('sites.reservations.cancel');

    // Gestion des hébergements
    Route::resource('hebergements', AdminHebergementController::class);
    Route::post('/hebergements/{id}/toggle-visibility', [AdminHebergementController::class, 'toggleVisibility'])
        ->name('hebergements.toggle-visibility');
    Route::post('/hebergements/{hebergementId}/comments/{commentId}/toggle-visibility', [AdminHebergementController::class, 'hideComment'])
        ->name('hebergements.comments.toggle-visibility');
    Route::get('/hebergements-statistics', [AdminHebergementController::class, 'statistics'])->name('hebergements.statistics');
    Route::get('/hebergements-disponibilite/{year}/{month}', [AdminHebergementController::class, 'disponibiliteParMois'])->name('hebergements.disponibilite');
    // Gestion des chambres
    Route::resource('chambres', AdminChambreController::class);
    Route::post('/chambres/{id}/toggle-visibility', [AdminChambreController::class, 'toggleVisibility'])
        ->name('chambres.toggle-visibility');
    Route::post('/chambres/{id}/toggle-availability', [AdminChambreController::class, 'toggleAvailability'])
        ->name('chambres.toggle-availability');
    
    // Gestion des réservations d'hébergements
    Route::get('/reservations/hebergements', 'App\Http\Controllers\Admin\Hebergement\ReservationController@index')->name('hebergements.reservations');
    Route::get('/reservations/hebergements/{id}', 'App\Http\Controllers\Admin\Hebergement\ReservationController@index')->name('hebergements.reservations.show');
    Route::post('/reservations/hebergements/{id}/cancel', [AdminHebergementReservationController::class, 'cancel'])->name('hebergements.reservations.cancel');
    Route::get('/reservations-statistics/hebergements', [AdminHebergementReservationController::class, 'statistics'])->name('hebergements.reservations.statistics');



});

// web.php

// Routes communes
// Route::get('/', [UserController::class, 'index'])->name('index');
Route::get('/', [AccueilController::class, 'index'])->name('index');
Route::get('/sites', [SiteTouristiqueController::class, 'index'])->name('sites.index');
Route::get('/sites/{id}', [SiteTouristiqueController::class, 'show'])->name('sites.show');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

Route::get('/hebergements', [ChambreCatalogueController::class, 'index'])->name('chambres.index');
Route::get('/hebergements/search', [ChambreCatalogueController::class, 'search'])->name('chambres.search');
Route::get('/hebergements/{id}', [ChambreCatalogueController::class, 'show'])->name('chambres.show');

// Routes pour les événements culturels
Route::get('/evenements', [EvenementController::class, 'index'])->name('evenements.index');
Route::get('/evenements/{id}', [EvenementController::class, 'show'])->name('evenements.show');

Route::get('/traditions_coutumes', [TraditionCoutumeController::class, 'index'])->name('traditions_coutumes.index');
Route::get('/traditions_coutumes/{id}', [TraditionCoutumeController::class, 'show'])->name('traditions_coutumes.show');

// Routes pour les circuits
Route::get('/circuits', [CircuitController::class, 'index'])->name('circuits.index');
Route::get('/circuits/search-ajax', [CircuitController::class, 'searchAjax'])->name('circuits.search-ajax');
Route::get('/circuits/{id}', [CircuitController::class, 'show'])->name('circuits.show');

Route::get('/about', [AboutController::class, 'index'])->name('about');


// Routes pour la page Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');


Route::get('/contact/success', [ContactController::class, 'success'])->name('contact.success');




// Routes pour guides (middleware pour vérifier rôle "Guide Touristique")
Route::middleware(['auth', 'role:10'])->prefix('guide')->group(function () {
    Route::get('/dashboard', [GuideController::class, 'dashboard'])->name('guide.dashboard');
    Route::get('/reservations', [GuideController::class, 'reservations'])->name('guide.reservations');
    Route::get('/reservations/{id}', [GuideController::class, 'showReservation'])->name('guide.reservations.show');
    Route::put('/reservations/{id}/status', [GuideController::class, 'updateReservationStatus'])->name('guide.reservations.updateStatus');
});

// Routes pour touristes (middleware pour vérifier rôle "Touriste")
Route::middleware(['auth', 'role:12'])->group(function () {
        Route::get('/reservations/sites', [SiteTouristeReservationController::class, 'index'])->name('touriste.reservations.sites.index');
        Route::get('/sites/{id}/reserver', [SiteTouristeReservationController::class, 'create'])->name('touriste.reservations.sites.create');
        Route::post('/reservations/sites', [SiteTouristeReservationController::class, 'store'])->name('touriste.reservations.sites.store');
        Route::get('/reservations/sites/{id}', [SiteTouristeReservationController::class, 'show'])->name('touriste.reservations.sites.show');
        Route::get('/reservations/sites/{id}/edit', [SiteTouristeReservationController::class, 'edit'])->name('touriste.reservations.sites.edit');
        Route::put('/reservations/sites/{id}', [SiteTouristeReservationController::class, 'update'])->name('touriste.reservations.sites.update');
        Route::delete('/reservations/sites/{id}/cancel', [SiteTouristeReservationController::class, 'cancel'])->name('touriste.reservations.sites.cancel');
}); 

// Routes pour les commentaires
Route::prefix('commentaires')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [CommentaireController::class, 'store']);
    Route::put('/{id}', [CommentaireController::class, 'update']);
    Route::delete('/{id}', [CommentaireController::class, 'destroy']);
    Route::patch('/{id}/visibility', [CommentaireController::class, 'toggleVisibility']);
    Route::get('/user', [CommentaireController::class, 'userComments']);
    Route::get('/can-comment', [CommentaireController::class, 'canComment']);
    
    // Route pour admin uniquement
    Route::get('/admin', [CommentaireController::class, 'adminIndex'])->middleware('admin');
});

Route::get('/commentaires', [CommentaireController::class, 'index']);



Route::prefix('proprietaire')->name('proprietaire.')->group(function () {
    // Gestion des hébergements
    Route::resource('hebergements', ProprietaireHebergementController::class);
    Route::delete('/hebergements/{hebergement}/medias/{media}', [ProprietaireHebergementController::class, 'deleteMedia'])
        ->name('hebergements.delete-media');
    
    // Gestion des chambres
    Route::resource('hebergements.chambres', ProprietaireChambreController::class);
    Route::post('/hebergements/{hebergement}/chambres/{chambre}/toggle-visibility', [ProprietaireChambreController::class, 'toggleVisibility'])
        ->name('hebergements.chambres.toggle-visibility');
    Route::post('/hebergements/{hebergement}/chambres/{chambre}/toggle-availability', [ProprietaireChambreController::class, 'toggleAvailability'])
        ->name('hebergements.chambres.toggle-availability');
    Route::delete('/hebergements/{hebergement}/chambres/{chambre}/medias/{media}', [ProprietaireChambreController::class, 'deleteMedia'])
        ->name('hebergements.chambres.delete-media');
    
    // Gestion des réservations
    Route::get('/reservations', [ProprietaireReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{id}', [ProprietaireReservationController::class, 'show'])->name('reservations.show');
    Route::get('/hebergements/{hebergement}/reservations', [ProprietaireReservationController::class, 'byHebergement'])
        ->name('hebergements.reservations.index');
    Route::get('/hebergements/{hebergement}/chambres/{chambre}/reservations', [ProprietaireReservationController::class, 'byChambre'])
        ->name('hebergements.chambres.reservations.index');
});


Route::prefix('reservations/hebergements')->name('touriste.')->group(function () {
    Route::get('/create/{chambre_id}', [TouristeHebergementReservationController::class, 'create'])->name('reservations.hebergements.create');
    Route::post('/', [TouristeHebergementReservationController::class, 'store'])->name('reservations.hebergements.store');
    Route::get('/', [TouristeHebergementReservationController::class, 'index'])->name('reservations.hebergements.index');
    Route::get('/{id}', [TouristeHebergementReservationController::class, 'show'])->name('reservations.hebergements.show');
    Route::get('/{id}/edit', [TouristeHebergementReservationController::class, 'edit'])->name('reservations.hebergements.edit');
    Route::put('/{id}', [TouristeHebergementReservationController::class, 'update'])->name('reservations.hebergements.update');
    Route::get('/{id}/confirm-cancel', [TouristeHebergementReservationController::class, 'confirmCancel'])->name('reservations.hebergements.confirm-cancel');
    Route::post('/{id}/cancel', [TouristeHebergementReservationController::class, 'cancel'])->name('reservations.hebergements.cancel');
    
    // Gestion des paiements
    Route::get('/paiements/initier/{reservation}', [PaiementController::class, 'initier'])->name('paiements.initier');
    Route::post('/paiements/confirmer/{reservation}', [PaiementController::class, 'confirmer'])->name('paiements.confirmer');
    Route::get('/paiements/historique', [PaiementController::class, 'historique'])->name('paiements.historique');
});

// Webhook pour les paiements (route publique)
Route::post('/webhook/paiement', [PaiementController::class, 'webhook'])->name('webhook.paiement');

// Routes pour les réservations de circuits (groupe avec préfixe et middleware)
Route::middleware(['auth', 'verified', 'role:12'])->prefix('reservations/circuits')->name('touriste.reservations.circuits.')->group(function () {
    // Liste des réservations
    Route::get('/', [CircuitReservationController::class, 'index'])->name('index');
    
    // Création d'une réservation
    Route::get('/create/{circuit}', [CircuitReservationController::class, 'create'])->name('create');
    Route::post('/', [CircuitReservationController::class, 'store'])->name('store');
    
    // Affichage des détails d'une réservation
    Route::get('/{id}', [CircuitReservationController::class, 'show'])->name('show');
    
    // Modification d'une réservation
    Route::get('/{id}/edit', [CircuitReservationController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CircuitReservationController::class, 'update'])->name('update');
    
    // Annulation d'une réservation
    Route::get('/{id}/cancel', [CircuitReservationController::class, 'confirmCancel'])->name('confirm-cancel');
    Route::post('/{id}/cancel', [CircuitReservationController::class, 'cancel'])->name('cancel');


    
});
