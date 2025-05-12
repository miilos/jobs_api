<?php

namespace Milos\JobsApi\Services;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = 'sandbox.smtp.mailtrap.io';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $_ENV['MAILTRAP_USERNAME'];
        $this->mailer->Password = $_ENV['MAILTRAP_PASSWORD'];
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->Port = 587;
    }

    public function send(string $recipientAddress, string $recipientName, string $subject, string $body): void
    {
        $this->mailer->setFrom($_ENV['EMAIL_FROM_ADDRESS'], $_ENV['EMAIL_FROM_NAME']);
        $this->mailer->addAddress($recipientAddress, $recipientName);

        $this->mailer->isHTML(false);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;

        $this->mailer->send();
    }
}