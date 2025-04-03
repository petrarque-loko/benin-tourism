<?php

namespace App\Notifications;

use App\Models\Commentaire;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationModifiee extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reservation;

    /**
     * Create a new notification instance.
     *
     * @param Reservation $reservation
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
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
        $elementReserve = $this->reservation->reservable_type == 'App\\Models\\SiteTouristique' 
            ? 'site touristique' 
            : 'circuit';
        
        return (new MailMessage)
                    ->subject('Réservation modifiée')
                    ->line('Un touriste a modifié sa réservation pour un ' . $elementReserve . '.')
                    ->line('Élément réservé : ' . $this->reservation->reservable->nom)
                    ->line('Nouvelles dates : du ' . $this->reservation->date_debut->format('d/m/Y') . ' au ' . $this->reservation->date_fin->format('d/m/Y'))
                    ->action('Voir les détails', url('/guide/reservations/' . $this->reservation->id))
                    ->line('Veuillez approuver ou rejeter cette demande modifiée.');
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
            'type' => 'reservation_modifiee',
            'reservation_id' => $this->reservation->id,
            'utilisateur_id' => $this->reservation->user_id,
            'utilisateur_nom' => $this->reservation->user->name,
            'message' => 'Réservation modifiée par un touriste'
        ];
    }
}