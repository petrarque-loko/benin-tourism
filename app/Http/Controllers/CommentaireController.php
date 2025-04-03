<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Reservation;
use App\Models\User;
use App\Models\SiteTouristique;
use App\Models\Circuit;
use App\Models\Hebergement;
use App\Models\Evenement;
use App\Notifications\NouveauCommentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentaireController extends Controller
{
    /**
     * Afficher les commentaires pour un élément spécifique
     */
    public function index(Request $request)
    {
        $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
        ]);

        $commentableType = $this->getCommentableModel($request->commentable_type);
        $commentableId = $request->commentable_id;

        // Vérifier si l'élément existe
        $commentable = $commentableType::find($commentableId);
        if (!$commentable) {
            return response()->json([
                'success' => false,
                'message' => "L'élément à commenter n'existe pas"
            ], 404);
        }
   
        // Récupérer les commentaires avec l'utilisateur associé
        $commentaires = Commentaire::where('commentable_type', $commentableType)
            ->where('commentable_id', $commentableId)
            ->where('is_hidden', false)
            ->with('user:id,nom,prenom')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculer la note moyenne
        $noteMoyenne = $commentaires->avg('note');

        return response()->json([
            'success' => true,
            'commentaires' => $commentaires,
            'note_moyenne' => round($noteMoyenne, 1),
            'total_commentaires' => $commentaires->count()
        ]);
    }

    /**
     * Stocker un nouveau commentaire
     */
    public function store(Request $request)
    {   
        try {

            $request->validate([
                'contenu' => 'required|string|min:3',
                'note' => 'required|integer|min:1|max:5',
                'commentable_type' => 'required|string',
                'commentable_id' => 'required|integer',
            ]);

            $user = Auth::user();
            $commentableType = $this->getCommentableModel($request->commentable_type);
            $commentableId = $request->commentable_id;

            // Vérifier si l'élément existe
            $commentable = $commentableType::find($commentableId);
            if (!$commentable) {
                return response()->json([
                    'success' => false,
                    'message' => "L'élément à commenter n'existe pas"
                ], 404);
            }

            // Vérifier si l'utilisateur a déjà commenté cet élément
            $existingComment = Commentaire::where('user_id', $user->id)
                ->where('commentable_type', $commentableType)
                ->where('commentable_id', $commentableId)
                ->first();

            if ($existingComment) {
                return response()->json([
                    'success' => false,
                    'message' => "Vous avez déjà commenté cet élément"
                ], 400);
            }

            // Vérifier si le touriste a une réservation terminée incluant l'élément
            if ($user->role->name === 'Touriste' && !$this->touristeCanComment($user, $commentableType, $commentableId)) {
                return response()->json([
                    'success' => false,
                    'message' => "Vous devez avoir une réservation terminée incluant cet élément pour pouvoir le commenter"
                ], 403);
            }

            // Créer le commentaire
            $commentaire = new Commentaire();
            $commentaire->contenu = $request->contenu;
            $commentaire->note = $request->note;
            $commentaire->user_id = $user->id;
            $commentaire->commentable_type = $commentableType;
            $commentaire->commentable_id = $commentableId;
            $commentaire->save();

            // Notifier l'administrateur
            $admins = User::where('role_id', 9)->get();
            foreach ($admins as $admin) {
                $admin->notify(new NouveauCommentaire($commentaire));
            }



            return response()->json([
                'success' => true,
                'message' => "Votre commentaire a été ajouté avec succès",
                'commentaire' => $commentaire->load('user:id,nom,prenom')
            ], 201);
        }
        catch (\Exception $e) { 
            return response()->json([
                'success' => false,
                'message' => "Erreur: " . $e->getMessage(),
                'debug' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Mettre à jour un commentaire existant
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'contenu' => 'required|string|min:3',
            'note' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();
        $commentaire = Commentaire::find($id);

        if (!$commentaire) {
            return response()->json([
                'success' => false,
                'message' => "Le commentaire n'existe pas"
            ], 404);
        }

        // Vérifier si l'utilisateur est propriétaire du commentaire
        if ($commentaire->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => "Vous n'êtes pas autorisé à modifier ce commentaire"
            ], 403);
        }

        // Mettre à jour le commentaire
        $commentaire->contenu = $request->contenu;
        $commentaire->note = $request->note;
        $commentaire->save();

        return response()->json([
            'success' => true,
            'message' => "Votre commentaire a été mis à jour avec succès",
            'commentaire' => $commentaire->load('user:id,nom,prenom')
        ]);
    }

    /**
     * Supprimer un commentaire
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $commentaire = Commentaire::find($id);

        if (!$commentaire) {
            return response()->json([
                'success' => false,
                'message' => "Le commentaire n'existe pas"
            ], 404);
        }

        // Vérifier si l'utilisateur est propriétaire du commentaire ou administrateur
        if ($commentaire->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => "Vous n'êtes pas autorisé à supprimer ce commentaire"
            ], 403);
        }

        $commentaire->delete();

        return response()->json([
            'success' => true,
            'message' => "Le commentaire a été supprimé avec succès"
        ]);
    }

    /**
     * Masquer/démasquer un commentaire (pour les administrateurs)
     */
    public function toggleVisibility($id)
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur est administrateur
        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => "Vous n'êtes pas autorisé à effectuer cette action"
            ], 403);
        }

        $commentaire = Commentaire::find($id);

        if (!$commentaire) {
            return response()->json([
                'success' => false,
                'message' => "Le commentaire n'existe pas"
            ], 404);
        }

        // Inverser la visibilité
        $commentaire->is_hidden = !$commentaire->is_hidden;
        $commentaire->save();

        $status = $commentaire->is_hidden ? 'masqué' : 'visible';

        return response()->json([
            'success' => true,
            'message' => "Le commentaire est maintenant $status",
            'is_hidden' => $commentaire->is_hidden
        ]);
    }

    /**
     * Vérifier si un touriste peut commenter un élément
     */
    private function touristeCanComment($user, $commentableType, $commentableId) {
        // Vérifier selon le type d'élément
        switch ($commentableType) {
            case 'App\Models\SiteTouristique':
                // Vérifier s'il y a une réservation directe du site avec statut terminé
                $directReservation = Reservation::where('user_id', $user->id)
                    ->where('statut', 'terminé')
                    ->where('reservable_type', $commentableType)
                    ->where('reservable_id', $commentableId)
                    ->exists();
                    
                    $circuitReservation = Reservation::where('user_id', $user->id)
                    ->where('statut', 'terminé')
                    ->where('reservable_type', 'App\\Models\\Circuit')
                    ->whereHasMorph('reservable', [Circuit::class], function ($query) use ($commentableId) {
                        $query->whereHas('sitesTouristiques', function ($q) use ($commentableId) {
                            $q->where('sites_touristiques.id', $commentableId); // Ajout du préfixe de table
                        });                        
                    })
                    ->exists();
                
                    
                // Retourner vrai si l'une des conditions est satisfaite
                return $directReservation || $circuitReservation;
                
            case 'App\Models\User': // Guide touristique
                return Reservation::where('user_id', $user->id)
                    ->where('statut', 'terminé')
                    ->where('guide_id', $commentableId)
                    ->exists();
                    
            case 'App\Models\Circuit':
            case 'App\Models\Hebergement':
            case 'App\Models\Evenement':
                return Reservation::where('user_id', $user->id)
                    ->where('statut', 'terminé')
                    ->where('reservable_type', $commentableType)
                    ->where('reservable_id', $commentableId)
                    ->exists();
                    
            default:
                return false;
        }
    }

    /**
     * Obtenir le modèle complet à partir du type
     */
    private function getCommentableModel($type)
    {
        switch ($type) {
            case 'site':
                return 'App\Models\SiteTouristique';
            case 'guide':
                return 'App\Models\User';
            case 'circuit':
                return 'App\Models\Circuit';
            case 'hebergement':
                return 'App\Models\Hebergement';
            case 'evenement':
                return 'App\Models\Evenement';
            default:
                throw ValidationException::withMessages([
                    'commentable_type' => ['Type d\'élément invalide']
                ]);
        }
    }

    /**
     * Récupérer les commentaires pour le dashboard utilisateur
     */
    public function userComments()
    {
        $user = Auth::user();
        
        $commentaires = Commentaire::where('user_id', $user->id)
            ->with(['commentable'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'success' => true,
            'commentaires' => $commentaires
        ]);
    }

    /**
     * Récupérer tous les commentaires pour l'administration
     */
    public function adminIndex(Request $request)
    {
        $query = Commentaire::with(['user:id,nom,prenom', 'commentable']);
        
        // Filtres
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('type')) {
            $commentableType = $this->getCommentableModel($request->type);
            $query->where('commentable_type', $commentableType);
        }
        
        if ($request->has('is_hidden')) {
            $query->where('is_hidden', $request->is_hidden === 'true');
        }

        $commentaires = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json([
            'success' => true,
            'commentaires' => $commentaires
        ]);
    }

    /**
 * Vérifier si l'utilisateur peut commenter un élément
 */
public function canComment(Request $request)
{
    $request->validate([
        'commentable_type' => 'required|string',
        'commentable_id' => 'required|integer',
    ]);

    $user = Auth::user();
    $commentableType = $this->getCommentableModel($request->commentable_type);
    $commentableId = $request->commentable_id; 

    // Vérifier si l'élément existe
    $commentable = $commentableType::find($commentableId);
    if (!$commentable) {
        return response()->json([
            'success' => false,
            'can_comment' => false,
            'message' => "L'élément n'existe pas"
        ], 404);
    }

    // Vérifier si l'utilisateur a déjà commenté cet élément
    $existingComment = Commentaire::where('user_id', $user->id)
        ->where('commentable_type', $commentableType)
        ->where('commentable_id', $commentableId)
        ->first();

    if ($existingComment) {
        return response()->json([
            'success' => true,
            'can_comment' => false,
            'message' => "Vous avez déjà commenté cet élément"
        ]);
    }

    // Vérifier si le touriste a une réservation terminée incluant l'élément
    $canComment = true;
    if ($user->role->name === 'Touriste') {
        $canComment = $this->touristeCanComment($user, $commentableType, $commentableId);
    }

    return response()->json([
        'success' => true,
        'can_comment' => $canComment,
        'message' => $canComment ? "Vous pouvez commenter cet élément" : "Vous devez avoir une réservation terminée incluant cet élément pour pouvoir le commenter"
    ]);
}
}
