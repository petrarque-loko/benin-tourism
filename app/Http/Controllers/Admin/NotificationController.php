<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
class NotificationController extends Controller
{
    /**
     * Récupère les notifications pour le panneau d'administration.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? 'general',
                    'message' => $notification->data['message'] ?? 'Notification',
                    'notifiable_id' => $notification->data['notifiable_id'] ?? null,
                    'read' => !is_null($notification->read_at),
                    'created_at' => $notification->created_at
                ];
            });

        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
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

