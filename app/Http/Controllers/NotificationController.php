<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Récupère les notifications récentes pour l'affichage dans la cloche de notification.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRecentNotifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Récupère uniquement le nombre de notifications non lues.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUnreadCount()
    {
        $unreadCount = auth()->user()->unreadNotifications()->count();
        
        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Récupère toutes les notifications de l'utilisateur connecté.
     *
     * @return \Illuminate\View\View
     */
    public function getAllNotifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marque toutes les notifications comme lues.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marque une notification spécifique comme lue.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
}