<?php

namespace App\Service;

use SendGrid\Mail\Mail;
use SendGrid\Client;
use Exception;
use SendGrid\Mail\TypeException;

class SendGridService
{
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @throws TypeException
     */
    public function sendEmail(string $toEmail, string $toName, float $amount): bool
    {
        $email = new Mail();
        $email->setFrom("from@example.com", "From Name");
        $email->setSubject("Payment Status");
        $email->addTo($toEmail, $toName);
        $email->addContent("text/plain", "Dear $toName, your payment of $amount has been $paymentStatus.");

        $sendgrid = new Client($this->apiKey);
        try {
            $response = $sendgrid->send($email);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
