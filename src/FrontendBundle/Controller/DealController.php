<?php

namespace FrontendBundle\Controller;

use BackendBundle\Model\SortingMode;
use FrontendBundle\Form\DealFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * {@inheritDoc}
 */
class DealController extends Controller
{
    const FILTER_SESSION = 'deal_filter';

    public function indexAction(Request $request)
    {
        $sortMode = $request->get('order', SortingMode::SORT_NONE);

        if (false === SortingMode::hasValue($sortMode)) {
            $sortMode = SortingMode::SORT_NONE;
        }

        $form = $this->createForm(new DealFilter());
        $form->handleRequest($request);
        $data = $form->getData();

        if (null !== $data) {
            $this->get('session')->set(
                self::FILTER_SESSION, $data
            );
        } else {
            if ($this->get('session')->has(self::FILTER_SESSION)) {
                $data = $this->get('session')->get(self::FILTER_SESSION);
            }
        }

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $this->get('deal.manager')->findMatchingDeals(
                null !== $data ? $data : [], (int)$sortMode
            ),
            $request->query->get('page', 1),
            $this->getParameter('deals.pagination.items')
        );

        $pagination->setTemplate('FrontendBundle::paginator.html.twig');

        return $this->render('FrontendBundle:Deal:index.html.twig', [
            'deals' => $pagination,
            'form'  => $form->createView(),
        ]);
    }

    public function detailsAction(Request $request, $slug)
    {
        // TODO Detect when switching languages and translate the slug
        $deal = $this->get('deal.manager')->findBySlug($slug);

        $this->get('doctrine.orm.entity_manager')->flush();

        return $this->render('@Frontend/Deal/details.html.twig', [
            'deal' => $deal,
        ]);
    }
}
