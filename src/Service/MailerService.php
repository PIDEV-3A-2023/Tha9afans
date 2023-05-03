<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $replyTo;
    public function __construct(private MailerInterface $mailer) {

    }
    public function sendEmail(
        string $from,
        string $to ,
        string $subject ,
        string $template,
        array $content,
    ): void
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo($this->replyTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
//            ->text('Sending emails is fun again!')
            ->htmlTemplate("security/$template.html.twig")
            ->context($content);
        $this->mailer->send($email);
        // ...
    }

}