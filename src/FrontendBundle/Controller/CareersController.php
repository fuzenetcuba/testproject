<?php

namespace FrontendBundle\Controller;

use BackendBundle\Form\OpeningType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * {@inheritDoc}
 */
class CareersController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new OpeningType());

        $form->handleRequest($request);

        return $this->render('@Frontend/Careers/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function findAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $this->get('opening.manager')->findAllQuery(),
            $request->query->get('page', 1),
            $this->getParameter('deals.pagination.items')
        );

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
}
