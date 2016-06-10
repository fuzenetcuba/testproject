<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * {@inheritDoc}
 */
class BusinessController extends Controller
{
    public function indexAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $this->get('business.manager')->findAllQuery(),
            $request->query->get('page', 1),
            $this->getParameter('business.pagination.items')
        );

        $pagination->setTemplate('FrontendBundle::paginator.html.twig');

        return $this->render('FrontendBundle:Business:index.html.twig', [
            'businesses' => $pagination,
        ]);
    }

    public function detailsAction($slug)
    {
        $business = $this->get('business.manager')->findBySlug($slug);
        $relatedBusinesses = $this->get('business.manager')->findRelatedBusinesses($business);

        return $this->render('FrontendBundle:Business:details.html.twig', [
            'business' => $business,
            'related' => $relatedBusinesses,
        ]);
    }
}
