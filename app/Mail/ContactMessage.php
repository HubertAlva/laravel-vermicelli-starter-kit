<?php

namespace App\Mail;

use App\Data\ContactData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactData $data,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->data->email, $this->data->name)],
            subject: $this->data->subject ?: 'Nuevo mensaje de contacto',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact',
        );
    }
}
