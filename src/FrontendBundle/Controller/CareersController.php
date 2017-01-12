<?php

namespace FrontendBundle\Controller;

use BackendBundle\Entity\Opening;
use BackendBundle\Form\OpeningType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * {@inheritDoc}
 */
class CareersController extends Controller
{
    public function indexAction(Request $request)
    {
        if (!$this->getParameter('careers.apply.online')) {
            throw $this->createNotFoundException('Page not found!');
        }
        
        $form = $this->createForm(new OpeningType(true));
        $form->handleRequest($request);

        return $this->render('@Frontend/Careers/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function findAction(Request $request, $business)
    {
        $paginator = $this->get('knp_paginator');

        $form = $this->createForm(new OpeningType(true));
        $form->handleRequest($request);

        $conditions = [];

        if (null !== $business && null === $form->getData()) {
            $conditions = [
                'business' => $business
            ];
        } else if (null !== $form->getData()) {
            /** @var Opening $opening */
            $opening = $form->getData();

            $conditions = array_merge([
                'business' => null !== $opening->getBusiness() ? $opening->getBusiness()->getSlug() : null,
                'opening' => null !== $opening->getPosition() ? $opening->getPosition()->getId() : null,
            ], $conditions);
        }

        $pagination = $paginator->paginate(
            $this->get('opening.manager')->findMatchingOpenings($conditions),
            $request->query->get('page', 1),
            $this->getParameter('deals.pagination.items')
        );

        $pagination->setTemplate('FrontendBundle::paginator.html.twig');

        return $this->render('@Frontend/Careers/find.html.twig', [
            'openings' => $pagination
        ]);
    }

    public function applyAction(Request $request, $slug)
    {
        $opening = $this->get('opening.manager')->findBySlug($slug);

        return $this->render('@Frontend/Careers/apply.html.twig', [
            'opening' => $opening,
        ]);
    }

    public function storeAction(Request $request)
    {
        $candidate = $this->get('opening.manager')->fromRequest($request);

        $this->get('opening.manager')->saveCandidate($candidate);
        $content = $this->renderView('@Backend/Emails/customer.html.twig', [
            'content' => sprintf('%s Has applied to the %s position (%s)',
                $candidate->fullName(), $candidate->opening->getPosition(),
                (new \DateTime())->format('Y-m-d H:i:s'))
            ,
            'deals' => []
        ]);

        $this->get('opening.manager')->notifyManager(
            $candidate,
            $this->getParameter('careers.notification.subject'),
            $this->getParameter('customer.email.from'),
            $content
        );

        return new JsonResponse(['status' => 'ok']);
    }

    public function autocompleteAction(Request $request)
    {
        $position = $request->request->get('position');
        $business = $request->request->get('business');
        
        $businesses = [];
        $positions = [];

        if (0 === count($positions)) {
            if (null === $position || '-1' == $position) {
                $positions = $this->get('opening.manager')->findAllQuery()->getQuery()->getResult();
            } else {
                $positions = [$this->get('opening.manager')->find($position)];
                // fetch the matching businesses
                $businesses = $this->get('business.manager')->findBusinessWithOpening($positions[0]);
            }
        }

        if (0 === count($businesses)) {
            if ((null === $business || '-1' == $business)) {
                $businesses = $this->get('business.manager')->findAll();
            } else {
                $businesses = [$this->get('business.manager')->find($business)];
                // fetch the matching openings
                $positions = $this->get('opening.manager')->findOpeningsWithBusiness($businesses[0]);
            }
        }

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes([
            'categories',
            'deals',
            'customers',
            'openings',
        ]);

        $serializer = new Serializer([$normalizer], [$encoder]);

        return new JsonResponse([
            'business' => $serializer->serialize($businesses, 'json'),
            'position' => $serializer->serialize($positions, 'json')
        ]);
    }  
}
