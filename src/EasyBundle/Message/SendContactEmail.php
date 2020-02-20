<?php


namespace App\EasyBundle\Message;


class SendContactEmail
{
    private $bodyHtml;

    public function __construct($bodyHtml)
    {
        $this->bodyHtml = $bodyHtml;
    }

    public function getBodyHtml()
    {
        return $this->bodyHtml;
    }
}