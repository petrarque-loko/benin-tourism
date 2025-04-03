<?php

namespace App\Notifications;

use App\Models\Commentaire;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleReservation extends Notification implements ShouldQueue
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
        
        $nomElement = $this->reservation->reservable->nom;
        
        return (new MailMessage)
                    ->subject('Nouvelle demande de réservation')
                    ->line('Un utilisateur a fait une demande de réservation pour un ' . $elementReserve . '.')
                    ->line('Élément réservé : ' . $nomElement)
                    ->line('Du ' . $this->reservation->date_debut->format('d/m/Y') . ' au ' . $this->reservation->date_fin->format('d/m/Y'))
                    ->action('Voir les détails', url('/guide/reservations/' . $this->reservation->id))
                    ->line('Veuillez approuver ou rejeter cette demande.');
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
            'type' => 'reservation',
            'reservation_id' => $this->reservation->id,
            'utilisateur_id' => $this->reservation->user_id,
            'utilisateur_nom' => $this->reservation->user->name,
            'message' => 'Nouvelle demande de réservation'
        ];
    }
}