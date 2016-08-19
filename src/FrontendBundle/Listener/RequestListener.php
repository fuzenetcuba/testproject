<?php

namespace FrontendBundle\Listener;

use FrontendBundle\Controller\DealController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Removes the session persisted filters if a request not matching
 * the desired section matches
 *
 * @package \FrontendBundle\Listener
 */

class RequestListener
{
    private $session;
    private $lookupUriPart;
    private $sessionKey;

    public function __construct(SessionInterface $session, $lookupUriPart, $sessionKey)
    {
        $this->session = $session;
        $this->lookupUriPart = $lookupUriPart;
        $this->sessionKey = $sessionKey;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            // get out of here
            return;
        }

        if ($event->getRequest()->isXmlHttpRequest() ||
            true === preg_match('/page/', $event->getRequest()->getRequestUri()) ||
            1 === preg_match(sprintf('/%s/', $this->lookupUriPart), $event->getRequest()->getRequestUri())) {
            return ;
        } else {
            if ($this->session->has($this->sessionKey)) {
                $this->session->remove($this->sessionKey);
            }
        }
    }
}