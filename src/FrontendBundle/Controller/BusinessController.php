<?php

namespace FrontendBundle\Controller;

use BackendBundle\Model\SortingMode;
use FrontendBundle\Form\BusinessFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * {@inheritDoc}
 */
class BusinessController extends Controller
{
    const FILTER_SESSION = 'business_filter';

    public function indexAction(Request $request)
    {
        $sortMode = $request->get('order', SortingMode::SORT_ALPHABETICALLY);

        if (false === SortingMode::hasValue($sortMode)) {
            $sortMode = SortingMode::SORT_NONE;
        }

        $form = $this->createForm(new BusinessFilter());
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
            $this->get('business.manager')->findMatchingDeals(
                null !== $data ? $data : [], (int)$sortMode
            ),
            $request->query->get('page', 1),
            $this->getParameter('business.pagination.items')
        );

        $pagination->setTemplate('FrontendBundle::paginator.html.twig');

        return $this->render('FrontendBundle:Business:index.html.twig', [
            'businesses' => $pagination,
            'form' => $form->createView(),
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

    /**
     * Lists all Business entities for Map Positions.
     *
     */
    public function mapAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        if ($slug === "all") {
            $entities = $em->getRepository("BackendBundle:Business")->findAll();
        } else {
            $entities = $em->getRepository("BackendBundle:Business")->findBySlug($slug);
        }
        return $this->render('@Frontend/Business/map.html.twig', array(
            'entities' => $entities,
        ));
    }
}
