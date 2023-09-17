<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString;

class AbstractMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Request $request) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from:       new Address(...$this->request->from),
            to:         $this->request->to,
            subject:    $this->request->subject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $this->text(new HtmlString($this->request->text));

        return new Content(
            htmlString: $this->request->html
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
