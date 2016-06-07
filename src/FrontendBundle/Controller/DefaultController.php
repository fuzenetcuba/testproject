<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('FrontendBundle:Default:index.html.twig',
            array(
                'name' => 'Dear Public User',

                // last username entered by the user
                'last_username' => $lastUsername,
                'error' => $error,
            )
        );
    }

    public function logedInAction()
    {
        return $this->render('FrontendBundle:Default:index.html.twig', array('name' => 'Loged In User'));
    }
}
