<?php

namespace BackendBundle\Controller;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Feedback;
use BackendBundle\Form\FeedbackType;

/**
 * Feedback controller.
 *
 */
class FeedbackController extends Controller
{
    /**
     * Lists all Feedback entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:Feedback e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), $request->query->getInt('limit', $this->getParameter('cruds.pagination.items'))
        );

        return $this->render('feedback/index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Find Feedback entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');

        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:Feedback e WHERE "
                . "e.name LIKE '%" . $find . "%' OR "
                . "e.email LIKE '%" . $find . "%' "
                . "ORDER BY e.id ASC";
            $query = $em->createQuery($dql)
                ->setHint(
                    Query::HINT_CUSTOM_OUTPUT_WALKER,
                    'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
                );

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1), $request->query->getInt('limit', $this->getParameter('cruds.pagination.items'))
            );

            return $this->render('feedback/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('feedback'));
        }
    }

    /**
     * Displays a form to create a new Feedback entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Feedback();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The feedback was created successfully.');

            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('feedback_new'));
            } else {
                return $this->redirectToRoute('feedback_show', array('id' => $entity->getId()));
            }

        }

        return $this->render('feedback/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Feedback entity.
     *
     * @param Feedback $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Feedback $entity)
    {
        $form = $this->createForm('BackendBundle\Form\FeedbackType', $entity, array(
            'action' => $this->generateUrl('feedback_new'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));
        $form->add('submitback', SubmitType::class, array('label' => 'Create & Back'));

        return $form;
    }

    /**
     * Finds and displays a Feedback entity.
     *
     */
    public function showAction(Feedback $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('feedback/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Feedback entity.
     *
     */
    public function editAction(Request $request, Feedback $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The feedback was updated successfully.');
            return $this->redirectToRoute('feedback_show', array('id' => $entity->getId()));
        }

        return $this->render('feedback/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Feedback entity.
     *
     * @param Feedback $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Feedback $entity)
    {
        $form = $this->createForm('BackendBundle\Form\FeedbackType', $entity, array(
            'action' => $this->generateUrl('feedback_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Feedback entity.
     *
     */
    public function deleteAction(Request $request, Feedback $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feedback entity.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The feedback was deleted successfully.');
        return $this->redirect($this->generateUrl('feedback'));


    }

    /**
     * Creates a form to delete a Feedback entity.
     *
     * @param Feedback $entity The Feedback entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Feedback $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('feedback_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Do several batch actions over Feedback entities.
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
                    $entity = $em->getRepository('BackendBundle:Feedback')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Feedback entity.');
                    } else {
                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Feedbacks deleted successfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('feedback'));
    }
}
