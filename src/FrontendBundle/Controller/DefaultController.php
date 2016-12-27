<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        $featuredBrands = [];
        $featuredBrands[] = $this->get('business.manager')->findBySlug('xenote-restaurant');
        $featuredBrands[] = $this->get('business.manager')->findBySlug('tito-s-playland');
        $featuredBrands[] = $this->get('business.manager')->findBySlug('maz-fresco');

        return $this->render('FrontendBundle:Default:index.html.twig',
            array(
                'name' => 'Dear Public User',
                'last_username' => $lastUsername,
                'error' => $error,
                'deals' => $topDeals,
                'featured_brands' => $featuredBrands
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
     * Displays robots.txt.
     */
    public function robotsAction($template = null)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        return $this->render($template ?: sprintf(
            "FrontendBundle:Static:robots_%s.txt.twig",
            $this->container->getParameter('kernel.environment')
        ), array(), $response);
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

        if($hl !== null){
            $businessData = $this->get('business.manager')->findBySlug($hl);
        } else {
            $businessData = null;
        }

        $serializer = new Serializer([$normalizer], [$encoder]);

        return $this->render('@Frontend/map.html.twig', [
            'businesses' => $serializer->serialize($businesses, 'json'),
            'highlight' => $hl,
            'businessData' => $businessData
        ]);
    }



    /**
     * Displays Job Fair Page
     */
    public function jobfairAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->get('opening.manager')->findAllQuery([]),
            $request->query->get('page', 1),
            $this->getParameter('deals.pagination.items')
        );

        return $this->render("FrontendBundle:Static:jobfair.html.twig", [
            'openings' => $pagination
        ]);
    }
}
