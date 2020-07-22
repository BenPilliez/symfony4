<?php

namespace App\Email;

use App\Entity\Application;

class ApplicationMailer
{
    /**
     * @var \Swift_mailer
     */

    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {

        $this->mailer = $mailer;
    }

    public function sendNewNotificationMailer(Application $application)
    {
        $message = new \Swift_Message(
            'Nouvelle candidature',
            'Vous avez reçu une nouvelle candidature'
        );

        $message
        ->addTo($application->getAdvert()->getEmail())
        ->addFrom('admin@votresite.com');

        $this->mailer->send($message);
    }
}
