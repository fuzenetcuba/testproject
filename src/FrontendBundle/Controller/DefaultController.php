<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    /**
     * Handles the logic of the mall map page
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     */
    public function mapAction(Request $request, $hl = null)
    {
        $businesses = $this->get('business.manager')->findAll();

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes([
            'categories',
            'deals',
            'customers',
        ]);

        $serializer = new Serializer([$normalizer], [$encoder]);

        return $this->render('@Frontend/map.html.twig', [
            'businesses' => $serializer->serialize($businesses, 'json'),
            'highlight' => $hl
        ]);
    }
}
