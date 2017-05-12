<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Image;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Business;
use BackendBundle\Form\BusinessType;

/**
 * Business controller.
 *
 */
class BusinessController extends Controller
{
    /**
     * Lists all Business entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:Business e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), $this->getParameter('cruds.pagination.items')
        );

        return $this->render('business/index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Find Business entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');

        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:Business e WHERE "
                . "e.name LIKE '%" . $find . "%' OR "
                . "e.website LIKE '%" . $find . "%' OR "
                . "e.email LIKE '%" . $find . "%' "
                . "ORDER BY e.id ASC";
            $query = $em->createQuery($dql)
                ->setHint(
                    Query::HINT_CUSTOM_OUTPUT_WALKER,
                    'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
                );

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1), $this->getParameter('cruds.pagination.items')
            );

            return $this->render('business/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('business'));
        }
    }

    /**
     * Displays a form to create a new Business entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Business();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The business was created successfully.');

            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('business_new'));
            } else {
                return $this->redirectToRoute('business_show', array('id' => $entity->getId()));
            }

        }

        return $this->render('business/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Business entity.
     *
     * @param Business $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Business $entity)
    {
        $form = $this->createForm('BackendBundle\Form\BusinessType', $entity, array(
            'action' => $this->generateUrl('business_new'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));
        $form->add('submitback', SubmitType::class, array('label' => 'Create & Back'));

        return $form;
    }

    /**
     * Finds and displays a Business entity.
     *
     */
    public function showAction(Business $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        $imageEntity = new Image();
        $imageEntity->setBusiness($entity);

        $imageForm = $this->createForm('BackendBundle\Form\ImageType', $imageEntity, array(
            'action' => $this->generateUrl('business_upload_image', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        return $this->render('business/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
            'image_form' => $imageForm->createView(),
        ));
    }

    public function uploadImageAction(Request $request, Business $business)
    {
        $deleteForm = $this->createDeleteForm($business);

        $entity = new Image();
        $entity->setBusiness($business);

        $form = $this->createForm('BackendBundle\Form\ImageType', $entity, array(
            'action' => $this->generateUrl('business_upload_image', array('id' => $business->getId())),
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The image of the business was uploaded successfully.');

            return $this->redirectToRoute('business_show', array('id' => $business->getId()));

        }

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('danger', 'The image have one or more problems. Got to the upload view to look error details on fields');

        return $this->render('business/show.html.twig', array(
            'entity' => $business,
            'delete_form' => $deleteForm->createView(),
            'image_form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Business entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \BackendBundle\Entity\Business $entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function editAction(Request $request, Business $entity)
    {
        $locale = $request->get('_locale');

        $entity->setTranslatableLocale($locale);
        $this->get('doctrine.orm.entity_manager')->refresh($entity);

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The business was updated successfully.');
            return $this->redirectToRoute('business_show', array('id' => $entity->getId()));
        }

        return $this->render('business/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Business entity.
     *
     * @param Business $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Business $entity)
    {
        $form = $this->createForm('BackendBundle\Form\BusinessType', $entity, array(
            'action' => $this->generateUrl('business_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Business entity.
     *
     */
    public function deleteAction(Request $request, Business $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Business entity.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The business was deleted successfully.');
        return $this->redirect($this->generateUrl('business'));


    }

    /**
     * Creates a form to delete a Business entity.
     *
     * @param Business $entity The Business entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Business $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('business_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Do several batch actions over Business entities.
     *
     */
    public function batchAction(Request $request)
    {
        $action = $request->get('batch_action_do');
        $ids = $request->get('batch_action_checkbox');
        $recordsSelected = false;

        if ($ids) {
            $em = $this->getDoctrine()->getManager();

            if ($action == "delete") {
                foreach ($ids as $id) {
                    $entity = $em->getRepository('BackendBundle:Business')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Business entity.');
                    } else {
                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Businesss deleted successfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('business'));
    }
}
