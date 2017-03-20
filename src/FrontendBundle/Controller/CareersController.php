<?php

namespace FrontendBundle\Controller;

use BackendBundle\Entity\Opening;
use BackendBundle\Entity\OpeningCategory;
use BackendBundle\Entity\SystemEvents;
use BackendBundle\Form\OpeningType;
use Doctrine\Common\Collections\ArrayCollection;
use FrontendBundle\Event\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormEvent;
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

            if ($opening->getCategories() instanceof OpeningCategory) {
                $categories = null !== $opening->getCategories() ? $opening->getCategories()->getId() : null;
            } else if ($opening->getCategories() instanceof ArrayCollection) {
                $categories = 0 !== $opening->getCategories()->count() ? $opening->getCategories()->first()->getId() : null;
            }

            $conditions = array_merge([
                'business' => null !== $opening->getBusiness() ? $opening->getBusiness()->getSlug() : null,
                'categories' => $categories,
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

        // validating the candidate data
        $validator = $this->get('validator');
        $errors = $validator->validate($candidate, null, array('application'));

        $errorsString = "";
        if (count($errors) > 0) {
            for ($i = 0; $i < count($errors); $i++) {
                $errorsString .= $errors->get($i)->getMessage();
            }
            return new JsonResponse(array('error' => $errorsString), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {

            $this->get('opening.manager')->storeFilesFromRequest($request, $candidate);
            $savedCandidate = $this->get('opening.manager')->saveCandidate($candidate);
            $content = $this->renderView('@Backend/Emails/customer.html.twig', [
                'content' => sprintf('%s Has applied to the %s position (%s)',
                    $candidate->fullName(), $candidate->getOpening()->getPosition(),
                    (new \DateTime())->format('Y-m-d H:i:s'))
                ,
                'deals' => []
            ]);

            $fileContent = $this->renderView('candidate/pdf.html.twig', array(
                'entity' => $candidate,    // $entity is the Candidate entity
            ));

            // Alert data
            $alertMessage = sprintf('<strong>%s</strong> has applied to the <strong>%s</strong> position <br /><small class="text-muted">%s</small>',
                $candidate->fullName(), $candidate->getOpening()->getPosition(),
                (new \DateTime())->format('F j, Y, g:i a'));
            $alertUrl = '/admin/candidate/'. $savedCandidate->getId() .'/show';

            // dispatching Alert event
            $this->get('event_dispatcher')->dispatch(SystemEvents::ALERT_EVENTS, new Event($alertMessage, $alertUrl));

            $this->get('opening.manager')->notifyManager(
                $candidate,
                $this->getParameter('careers.notification.subject'),
                $this->getParameter('customer.email.from'),
                $content,
                $fileContent
            );

            return new JsonResponse(['status' => 'ok']);
        }
    }

    public function autocompleteAction(Request $request)
    {
        $business = $request->request->get('business');
        $openingCategory = $request->request->get('categories');

        $businesses = [];
        $openingCategories = [];

        if (0 === count($openingCategories)) {
            if (null === $openingCategory || '-1' == $openingCategory) {
                $openingCategories = $this->get('opening.category.manager')->findAllQuery()->getQuery()->getResult();
            } else {
                $openingCategory = $this->get('opening.category.manager')->find($openingCategory);
                $openingCategories = $this->get('opening.category.manager')->findAllQuery()->getQuery()->getResult();
                // fetch the matching businesses
                $businesses = $this->get('business.manager')->findBusinessWithOpeningCategory($openingCategory);
            }
        }

        if (0 === count($businesses)) {
            if ((null === $business || '-1' == $business)) {
                $businesses = $this->get('business.manager')->findBusinessHaveOpenings();
            } else {
                $business = $this->get('business.manager')->find($business);
                $businesses = $this->get('business.manager')->findBusinessHaveOpenings();
                // fetch the matching openings
                $openingCategories = $this->get('opening.category.manager')->findOpeningsCategoryWithBusiness($business);
            }
        }

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes([
            'categories',
            'deals',
            'customers',
            'openings',
            'children',
            'parent',
        ]);

        $serializer = new Serializer([$normalizer], [$encoder]);

        return new JsonResponse([
            'business' => $serializer->serialize($businesses, 'json'),
            'categories' => $serializer->serialize($openingCategories, 'json')
        ]);
    }
}
