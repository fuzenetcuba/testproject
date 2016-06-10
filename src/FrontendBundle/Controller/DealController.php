<?php

namespace FrontendBundle\Controller;

use BackendBundle\Model\SortingMode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * {@inheritDoc}
 */
class DealController extends Controller
{
    public function indexAction(Request $request)
    {
        $sortMode = $request->get('order', SortingMode::SORT_NONE);

        if (false === SortingMode::hasValue($sortMode)) {
            $sortMode = SortingMode::SORT_NONE;
        }

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $this->get('deal.manager')->findAllSorted((int)$sortMode),
            $request->query->get('page', 1),
            $this->getParameter('deals.pagination.items')
        );

        $pagination->setTemplate('FrontendBundle::paginator.html.twig');

        return $this->render('FrontendBundle:Deal:index.html.twig', [
            'deals' => $pagination,
        ]);
    }

    public function detailsAction($slug)
    {
        $deal = $this->get('deal.manager')->findBySlug($slug);

        return $this->render('@Frontend/Deal/details.html.twig', [
            'deal' => $deal,
        ]);
    }
}
