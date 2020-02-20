<?php


namespace App\EasyBundle\Message;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class SendContactEmailHandler implements MessageHandlerInterface
{
    private CONST EMAIL_FROM = 'from@example.com';
    private CONST EMAIL_TO = 'to@example.com';
    private CONST EMAIL_SUBJECT = 'Contact subject';

    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(SendContactEmail $sendContactEmail)
    {
        $email = (new Email())
            ->from(self::EMAIL_FROM)
            ->to(self::EMAIL_TO)
            ->subject(self::EMAIL_SUBJECT)
            ->html($sendContactEmail->getHtml());

        $this->mailer->send($email);
    }
}