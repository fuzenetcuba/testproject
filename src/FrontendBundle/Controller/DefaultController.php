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

        $topDeals = $this->get('deal.manager')->findTopDeals();

        return $this->render('FrontendBundle:Default:index.html.twig',
            array(
                'name' => 'Dear Public User',
                'last_username' => $lastUsername,
                'error' => $error,
                'deals' => $topDeals,
            )
        );
    }

    public function logedInAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $topDeals = $this->get('deal.manager')->findTopDeals();

        return $this->render('FrontendBundle:Default:index.html.twig',
            array(
                'name' => 'Loged In User',
                'last_username' => $lastUsername,
                'error' => $error,
                'deals' => $topDeals,
            )
        );
    }

    public function staticPageAction($name)
    {
        return $this->render("FrontendBundle:Static:" . $name . ".html.twig");
    }
}
