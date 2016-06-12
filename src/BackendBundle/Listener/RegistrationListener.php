<?php

namespace BackendBundle\Listener;


use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_INITIALIZE => 'onRegistrationUserInitialize',
        );
    }

    public function onRegistrationUserInitialize(GetResponseUserEvent $event){
        $event->getUser()->addRole('ROLE_CUSTOMER');
    }
}