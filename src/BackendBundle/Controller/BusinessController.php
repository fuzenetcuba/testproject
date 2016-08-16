<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
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
            $query, $request->query->get('page', 1), 5
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
                . "e.description LIKE '%" . $find . "%' OR "
                . "e.website LIKE '%" . $find . "%' OR "
                . "e.email LIKE '%" . $find . "%' OR "
                . "e.socialMedia LIKE '%" . $find . "%' "
                . "ORDER BY e.id ASC";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1), 5
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
            $this->get('session')->getFlashBag()->add('success', 'The business was created succesfully.');

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

        return $this->render('business/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Business entity.
     *
     */
    public function editAction(Request $request, Business $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The business was updated succesfully.');
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
        $this->get('session')->getFlashBag()->add('success', 'The business was deleted succesfully.');
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
                    $this->get('session')->getFlashBag()->add('success', 'Businesss deleted succesfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('business'));
    }
}
