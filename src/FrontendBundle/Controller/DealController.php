<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * {@inheritDoc}
 */
class DealController extends Controller
{
    public function indexAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $this->get('deal.manager')->findAllQuery(),
            $request->query->get('page', 1),
            $this->getParameter('deals.pagination.items')
        );

        $pagination->setTemplate('FrontendBundle::paginator.html.twig');

        return $this->render('FrontendBundle:Deal:index.html.twig', array(
            'deals' => $pagination,
        ));
    }
}
