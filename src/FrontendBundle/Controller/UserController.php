<?php

namespace FrontendBundle\Controller;

use BackendBundle\Entity\RewardEvents;
use FrontendBundle\Event\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * {@inheritDoc}
 */
class UserController extends Controller
{
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        if (null === $user) {
            return $this->redirectToRoute('frontend_homepage');
        }

        return $this->render('FrontendBundle:User:index.html.twig', [
        ]);
    }
}
