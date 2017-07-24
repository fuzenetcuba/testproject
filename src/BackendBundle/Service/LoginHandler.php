<?php
/**
 * User: yulio
 * Date: 15/07/17
 * Time: 14:34
 */

namespace BackendBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManager;

class LoginHandler implements AuthenticationSuccessHandlerInterface
{

    private $router;
    private $em;
    private $session;
    private static $key;

    public function __construct(RouterInterface $router, EntityManager $em, ContainerInterface $container)
    {
        self::$key = '_security.backend.target_path';

        $this->router = $router;
        $this->em = $em;
        $this->session = $container->get('session');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        //check if the referrer session key has been set
        if ($this->session->has(self::$key)) {

            //set the url based on the link they were trying to access before being authenticated
            $route = $this->session->get(self::$key);

            //remove the session key
            $this->session->remove(self::$key);
            //if the referrer key was never set, redirect to a default route

        } else {
            $route = $this->router->generate('frontend_homepage');
            return new RedirectResponse($route);
        }

        return new RedirectResponse($route);

    }
}