<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Recuperación de Contraseña - Hackathon Platform')
                    ->view('emails.password-reset');
    }
}
