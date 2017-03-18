<?php

namespace BackendBundle\Listener;

use BackendBundle\Entity\Alert;
use BackendBundle\Entity\SystemEvents;
use Doctrine\ORM\EntityManager;
use FrontendBundle\Event\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AlertEventListener
 *
 * @package \FrontendBundle\Listener
 */
class AlertEventListener implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            SystemEvents::ALERT_EVENTS => 'onAlertEvent',
        ];
    }

    public function onAlertEvent(Event $event)
    {
        $alert = new Alert();
        $alert->setMessage($event->getSubject());
        $alert->setUrl($event->getName());

        $this->em->persist($alert);
        $this->em->flush();
    }
}