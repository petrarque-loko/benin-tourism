<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class ReservationCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reservation;

    /**
     * Create a new notification instance.
     *
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
        $site = $this->reservation->reservable;
        return (new MailMessage)
            ->subject('Annulation de votre réservation')
            ->line('Votre réservation pour la visite du site ' . $site->nom . ' a été annulée par l\'administrateur.')
            ->line('Raison de l\'annulation: ' . $this->reservation->raison_annulation)
            ->line('Si vous avez des questions, veuillez contacter notre service client.')
            ->action('Voir mes réservations', url('/reservations'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $site = $this->reservation->reservable;
        return [
            'type' => 'reservation_annulation',
            'message' => 'Réservation annulée: ' . $site->nom,
            'raison_annulation' => $this->reservation->raison_annulation,
            'reservation_id' => $this->reservation->id
        ];
    }
}