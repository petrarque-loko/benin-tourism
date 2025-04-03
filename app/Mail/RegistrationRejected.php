<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reason;

    public function __construct(User $user, $reason)
    {
        $this->user = $user;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->markdown('emails.registration-rejected')
            ->subject('Votre inscription sur Tourisme Bénin a été rejetée');
    }
}