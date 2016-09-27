<?php

namespace FrontendBundle\Listener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Mailer\Mailer;
use FrontendBundle\Controller\SubscriptionController;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\DataCollectorTranslator;

class UserSubscribedListener implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $message;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, $from, $subject, $message) 
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->from = $from;
        $this->subject = $subject;
        $this->message = $message;
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
            SubscriptionController::USER_SUBSCRIBED => 'onUserSubscribed',
        ];
    }

    public function onUserSubscribed(FormEvent $event)
    {
        $user = $event->getForm()->getData();

        $content = $this->templating->render('@Backend/Emails/customer.html.twig', [
            'content' => $this->message,
            'deals' => []
        ]);

        $message = \Swift_Message::newInstance()
            ->setSubject($this->subject)
            ->setFrom($this->from)
            ->setTo($user->getEmail())
            ->setBody($content, 'text/html')
        ;

        $this->mailer->send($message);
    }
}