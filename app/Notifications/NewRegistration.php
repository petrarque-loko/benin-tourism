<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewRegistration extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $role;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'registration',
            'message' => "Nouvelle inscription {$this->role->name} en attente d'approbation: {$this->user->prenom} {$this->user->nom}",
            'notifiable_id' => $this->user->id
        ];
    }
}