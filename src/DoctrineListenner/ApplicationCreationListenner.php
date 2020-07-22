<?php

namespace App\DoctrineListenner;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\Application;
use App\Email\ApplicationMailer;

class ApplicationCreationListenner
{
    /**
     * @var $applicationMailer
     */

    private $applicationMailer;

    public function __construct(ApplicationMailer $applicationMailer)
    {
        $this->applicationMailer = $applicationMailer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if(!$entity instanceof Application){
            return;
        }

        $this->applicationMailer->sendNewNotificationMailer($entity);

    }
}
