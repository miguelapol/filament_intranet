<?php

namespace App\Mail;
use App\Models\Holiday;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationHolidays extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public Holiday $holiday;

    public function __construct(Holiday $holiday)
    {
        $this->holiday = $holiday;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $this->holiday->load('user');
        return new Envelope(
            subject: 'Nueva solicitud de vacaciones de '.$this->holiday->user->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.holidays_template_correo',
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
