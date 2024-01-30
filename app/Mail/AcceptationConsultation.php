<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AcceptationConsultation extends Mailable
{
    use Queueable, SerializesModels;
    public $patient;
    public $medecin;
    public $planning;
    public $consultation;


    /**
     * Create a new message instance.
     */
    public function __construct($planning,$consultation, $medecin, $patient)
    {
        $this->patient = $patient;
        $this->medecin = $medecin;
        $this->planning = $planning;
        $this->consultation = $consultation;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Acceptation de Consultation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.AccepterConsultation',
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
