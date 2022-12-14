<?php

namespace App\Mail\Transports;

use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mime\Part\DataPart;

class GmailSmtpRelayTransport extends AbstractTransport
{
    /**
     * The underlying phpmailer instance.
     *
     * @var PHPMailer
     */
    public $mailer;

    public function __construct($config)
    {
        parent::__construct();

        $this->mailer = new PHPMailer(true);
        $this->mailer->CharSet = PHPMailer::CHARSET_UTF8;

        $this->mailer->SMTPKeepAlive = true;
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp-relay.gmail.com';
        $this->mailer->SMTPSecure = $config['encryption'];
        $this->mailer->Port = $config['port'];

        if ($config['username']) {
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $config['username'];
            $this->mailer->Password = $config['password'];
        }
    }

    protected function doSend(SentMessage $message): void
    {
        $this->flush();

        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $from = collect($email->getFrom())->first();

        $this->mailer->setFrom($from->getAddress(), $from->getName());

        collect($email->getTo())->each(function (Address $address) {
            $this->mailer->addAddress($address->getAddress());
        });

        collect($email->getReplyTo())->each(function (Address $address) {
            $this->mailer->addReplyTo($address->getAddress());
        });

        collect($email->getCc())->each(function (Address $address) {
            $this->mailer->addCC($address->getAddress());
        });

        collect($email->getBcc())->each(function (Address $address) {
            $this->mailer->addBCC($address->getAddress());
        });

        $this->mailer->isHTML();
        $this->mailer->Subject = $email->getSubject();
        $this->mailer->Body = $email->getHtmlBody();
        $this->mailer->AltBody = $email->getTextBody();

        collect($email->getAttachments())->each(function (DataPart $attachment) {
            $as = $attachment
                ->getPreparedHeaders()
                ->getHeaderParameter('content-type', 'name');

            $this->mailer->addStringAttachment($attachment->getBody(), $as);
        });

        $this->mailer->send();
    }

    /**
     * Flush all addresses and attachments.
     *
     * @return void
     */
    public function flush()
    {
        $this->mailer->clearAllRecipients();
        $this->mailer->clearAttachments();
    }

    public function __toString(): string
    {
        return 'smtp-relay.gmail.com';
    }
}
