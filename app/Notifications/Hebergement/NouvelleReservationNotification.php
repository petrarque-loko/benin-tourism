<?php

namespace App\Notifications\Hebergement;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleReservationNotification extends Notification
{
    use Queueable;

    protected $reservation;

    /**
     * Create a new notification instance.
     *
     * @param  Reservation  $reservation
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
        return ['database'];
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
            'reservation_id' => $this->reservation->id,
            'message' => 'Nouvelle réservation pour ' . $this->reservation->chambre->hebergement->nom,
            'date_debut' => $this->reservation->date_debut->format('d/m/Y'),
            'date_fin' => $this->reservation->date_fin->format('d/m/Y'),
            'nombre_personnes' => $this->reservation->nombre_personnes,
            'chambre' => $this->reservation->chambre->nom,
            'touriste' => $this->reservation->user->name,
        ];
    }
}