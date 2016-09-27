<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Settings;
use BackendBundle\Form\SettingsType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Settings controller.
 *
 */
class SettingsController extends Controller
{
    /**
     * Lists all Settings entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:Settings e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), 5
        );

        return $this->render('settings/index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Displays a form to create a new Settings entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Settings();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The settings was created succesfully.');

            return $this->redirectToRoute('settings_show', array('id' => $entity->getId()));
        }

        return $this->render('settings/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Settings entity.
     *
     * @param Settings $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Settings $entity)
    {
        $form = $this->createForm('BackendBundle\Form\SettingsType', $entity, array(
            'action' => $this->generateUrl('settings_new'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Finds and displays a Settings entity.
     *
     */
    public function showAction(Settings $entity)
    {
        return $this->render('settings/show.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Settings entity.
     *
     */
    public function editAction(Request $request, Settings $entity)
    {
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The settings was updated succesfully.');
            return $this->redirectToRoute('settings_show', array('id' => $entity->getId()));
        }

        return $this->render('settings/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Settings entity.
     *
     * @param Settings $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Settings $entity)
    {
        $form = $this->createForm('BackendBundle\Form\SettingsType', $entity, array(
            'action' => $this->generateUrl('settings_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Creates a form to edit a Settings entity.
     *
     */
    public function getSettingsAction($property){
        $em = $this->getDoctrine()->getRepository('BackendBundle:Settings')->findAll();
        $settings = $em[0];

        $getter = "get".ucfirst($property);

        return new Response($settings->$getter());
    }
}
