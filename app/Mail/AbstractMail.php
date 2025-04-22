<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ValidatedInput;

class AbstractMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public ValidatedInput $attributes) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from:       new Address(...$this->attributes->from),
            to:         $this->attributes->to,
            subject:    $this->attributes->subject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $this->text(new HtmlString($this->attributes->text));

        return new Content(
            htmlString: $this->attributes->html
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        // Todo: Support Attachments

        return [];
    }
}
