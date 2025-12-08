<?php

namespace App\Mail;

use App\Models\TeamJoinRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamJoinRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $joinRequest;
    public $team;
    public $applicant;
    public $role;

    /**
     * Create a new message instance.
     */
    public function __construct(TeamJoinRequest $joinRequest)
    {
        $this->joinRequest = $joinRequest;
        $this->team = $joinRequest->team;
        $this->applicant = $joinRequest->user;
        $this->role = $joinRequest->role;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva solicitud para unirse a tu equipo: ' . $this->team->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.team-join-request',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
