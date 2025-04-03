<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class ReservationStatusChanged extends Notification implements ShouldQueue
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
        $status = [
            'en_attente' => 'En attente',
            'approuvé' => 'Approuvée',
            'rejeté' => 'Rejetée',
            'annulé' => 'Annulée',
            'terminé' => 'Terminée'
        ];
        
        $elementReserve = $this->reservation->reservable_type == 'App\\Models\\SiteTouristique' 
            ? 'site touristique' 
            : 'circuit';
        
        $mailMessage = (new MailMessage)
            ->subject('Statut de votre réservation mis à jour')
            ->line('Le statut de votre réservation pour le ' . $elementReserve . ' "' . 
            ($this->reservation->reservable ? $this->reservation->reservable->nom : '[Nom non disponible]') . 
            '" a été mis à jour.')
                 ->line('Nouveau statut : ' . $status[$this->reservation->statut]);
            
        if (in_array($this->reservation->statut, ['rejeté', 'annulé']) && $this->reservation->raison_annulation) {
            $mailMessage->line('Raison : ' . $this->reservation->raison_annulation);
        }
        
        return $mailMessage
            ->action('Voir les détails', url('/reservations'))
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
            'type' => 'statut_reservation',
            'reservation_id' => $this->reservation->id,
            'statut' => $this->reservation->statut,
            'message' => 'Statut de votre réservation mis à jour'
        ];
    }
}