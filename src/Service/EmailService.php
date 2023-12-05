<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
private $mailer;

public function __construct(MailerInterface $mailer)
{
$this->mailer = $mailer;
}

public function sendEmail($recipient, $subject, $body)
{
$email = (new Email())
->from('imen.thmayni@esprit.tn')
->to($recipient)
->subject($subject)
->html($body);

$this->mailer->send($email);
}
}