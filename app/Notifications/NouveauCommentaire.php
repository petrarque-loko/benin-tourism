<?php

namespace App\Notifications;

use App\Models\Commentaire;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouveauCommentaire extends Notification implements ShouldQueue
{
    use Queueable;

    protected $commentaire;

    /**
     * Create a new notification instance.
     *
     * @param Commentaire $commentaire
     * @return void
     */
    public function __construct(Commentaire $commentaire)
    {
        $this->commentaire = $commentaire;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nouveau commentaire sur votre profil')
                    ->line('Un utilisateur a laissé un commentaire sur votre profil.')
                    ->line('Note : ' . $this->commentaire->note . '/5')
                    ->line('Commentaire : ' . $this->commentaire->contenu)
                    ->action('Voir le commentaire', url('/users/' . $notifiable->id))
                    ->line('Merci de votre confiance!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'commentaire',
            'commentaire_id' => $this->commentaire->id,
            'utilisateur_id' => $this->commentaire->user_id,
            'utilisateur_nom' => $this->commentaire->user->name,
            'note' => $this->commentaire->note,
            'message' => 'Nouveau commentaire'
        ];
    }
}