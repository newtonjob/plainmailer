<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ValidatedInput;
use Symfony\Component\Mime\Address as SymfonyAddress;

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
        $from = SymfonyAddress::create($this->attributes->from);

        return new Envelope(
            from:    new Address($from->getAddress(), $from->getName()),
            to:      $this->attributes->to,
            replyTo: $this->attributes->reply_to,
            subject: $this->attributes->subject,
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
        return $this->attributes->collect('attachments')->map(
            fn ($attachment) => Attachment::fromData(fn () => $attachment['content'], $attachment['filename'])
                ->withMime($attachment['content_type'])
        )->all();
    }
}
