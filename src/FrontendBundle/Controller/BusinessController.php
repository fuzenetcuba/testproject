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

        // split category get parameter by coma
        $splitCategories = explode(',', $request->get('category'));
        $category = count($splitCategories) > 1 ? $splitCategories : $splitCategories[0];

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

        if (null !== $category and !empty($category)) {
            if (!is_array($category)) {
                $catObject = $this->get('category.manager')->findBySlug($category);
                $data['category'] = $catObject->getId();
            } else {
                $catIds = array();
                foreach ($category as $i => $cat) {
                    $catObject = $this->get('category.manager')->findBySlug($cat);
                    $catIds[$i] = $catObject->getId();
                }

                $data['category'] = $catIds;
            }
        }

        $paginator = $this->get('knp_paginator');

        $businesses = $this->get('business.manager')->findMatchingDeals(
            null !== $data ? $data : [], (int)$sortMode
        );

        return $this->render('FrontendBundle:Business:index.html.twig', [
            'businesses' => $businesses,
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

}
