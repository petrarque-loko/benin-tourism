<?php

namespace App\Notifications\Hebergement;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservationModifieeNotification extends Notification
{
    use Queueable;

    protected $reservation;
    protected $anciennesDonnees;

    /**
     * Create a new notification instance.
     *
     * @param  Reservation  $reservation
     * @param  array  $anciennesDonnees
     * @return void
     */
    public function __construct(Reservation $reservation, array $anciennesDonnees)
    {
        $this->reservation = $reservation;
        $this->anciennesDonnees = $anciennesDonnees;
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
            'message' => 'Modification de la réservation pour ' . $this->reservation->chambre->hebergement->nom,
            'anciennes_donnees' => [
                'date_debut' => $this->anciennesDonnees['date_debut']->format('d/m/Y'),
                'date_fin' => $this->anciennesDonnees['date_fin']->format('d/m/Y'),
                'nombre_personnes' => $this->anciennesDonnees['nombre_personnes'],
            ],
            'nouvelles_donnees' => [
                'date_debut' => $this->reservation->date_debut->format('d/m/Y'),
                'date_fin' => $this->reservation->date_fin->format('d/m/Y'),
                'nombre_personnes' => $this->reservation->nombre_personnes,
            ],
            'chambre' => $this->reservation->chambre->nom,
            'touriste' => $this->reservation->user->name,
        ];
    }
}