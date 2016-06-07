<?php

namespace BackendBundle\Listener;


use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class InteractiveLoginListener
{
    private $router;
    private $autorization;

    public function __construct(Router $router, AuthorizationChecker $autorization)
    {
        $this->router = $router;
        $this->autorization = $autorization;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $frontend = "frontend_loged_in";
        $backend = "backend_homepage";
        if ($this->autorization->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->autorization->isGranted('ROLE_BUSSINES')) {
                $this->redirectRoute($event, $backend);
            } elseif ($this->autorization->isGranted('ROLE_USER')) {
                $this->redirectRoute($event, $frontend);
            }
        }
    }

    private function redirectRoute(InteractiveLoginEvent $event, $newRoute)
    {
        // authenticated (NON anonymous)
        $request = $event->getRequest();
        $request->request->set('_target_path', $newRoute);
    }
}