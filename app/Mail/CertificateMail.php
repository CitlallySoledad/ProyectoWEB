<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $memberName;
    public $teamName;
    public $eventName;
    public $place;
    public $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct($memberName, $teamName, $eventName, $place, $pdfPath)
    {
        $this->memberName = $memberName;
        $this->teamName = $teamName;
        $this->eventName = $eventName;
        $this->place = $place;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $placeText = $this->place == 1 ? '1er Lugar' : ($this->place == 2 ? '2do Lugar' : '3er Lugar');
        
        return new Envelope(
            subject: "🏆 Constancia de {$placeText} - {$this->eventName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.certificate',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if (file_exists($this->pdfPath)) {
            return [
                Attachment::fromPath($this->pdfPath)
                    ->as('Constancia_' . $this->place . '_Lugar.pdf')
                    ->withMime('application/pdf'),
            ];
        }
        
        return [];
    }
}
