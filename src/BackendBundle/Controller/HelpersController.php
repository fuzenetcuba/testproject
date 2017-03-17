<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Helpers;
use BackendBundle\Form\HelpersType;

/**
 * Helpers controller.
 *
 */
class HelpersController extends Controller
{
    /**
     * Lists all Helpers entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:Helpers e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), $this->getParameter('cruds.pagination.items')
        );

        return $this->render('helpers/index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Find Helpers entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');

        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:Helpers e WHERE "
                . "e.id LIKE '%" . $find . "%' OR "
                . "e.id LIKE '%" . $find . "%' "
                . "ORDER BY e.id ASC";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1), $this->getParameter('cruds.pagination.items')
            );

            return $this->render('Helpers/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('helpers'));
        }
    }

    /**
     * Displays a form to create a new Helpers entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Helpers();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The helpers was created successfully.');
            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('helpers_new'));
            } else {
                return $this->redirectToRoute('helpers_show', array('id' => $entity->getId()));
            }
        }

        return $this->render('helpers/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Helpers entity.
     *
     * @param Helpers $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Helpers $entity)
    {
        $form = $this->createForm('BackendBundle\Form\HelpersType', $entity, array(
            'action' => $this->generateUrl('helpers_new'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));
        $form->add('submitback', SubmitType::class, array('label' => 'Create & Back'));

        return $form;
    }

    /**
     * Finds and displays a Helpers entity.
     *
     */
    public function showAction(Helpers $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('helpers/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Helpers entity.
     *
     */
    public function editAction(Request $request, Helpers $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The helpers was updated successfully.');
            return $this->redirectToRoute('helpers_show', array('id' => $entity->getId()));
        }

        return $this->render('helpers/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Helpers entity.
     *
     * @param Helpers $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Helpers $entity)
    {
        $form = $this->createForm('BackendBundle\Form\HelpersType', $entity, array(
            'action' => $this->generateUrl('helpers_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Helpers entity.
     *
     */
    public function deleteAction(Request $request, Helpers $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Helpers entity.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The helpers was deleted successfully.');
        return $this->redirect($this->generateUrl('helpers'));
    }

    /**
     * Creates a form to delete a Helpers entity.
     *
     * @param Helpers $entity The Helpers entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Helpers $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('helpers_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Do several batch actions over Helpers entities.
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
                    $entity = $em->getRepository('BackendBundle:Helpers')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Helpers entity.');
                    } else {
                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Helperss deleted successfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('helpers'));
    }
}
