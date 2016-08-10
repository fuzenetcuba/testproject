<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Subscription;
use BackendBundle\Form\SubscriptionType;

/**
 * Subscription controller.
 *
 */
class SubscriptionController extends Controller
{
    /**
     * Lists all Subscription entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:Subscription e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), 5
        );

        return $this->render('subscription/index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Find Subscription entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');

        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:Subscription e WHERE "
                . "e.email LIKE '%" . $find . "%' "
                . "ORDER BY e.id ASC";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1), 5
            );

            return $this->render('subscription/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('subscription'));
        }
    }

    /**
     * Displays a form to create a new Subscription entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Subscription();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The subscription was created succesfully.');

            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('subscription_new'));
            } else {
                return $this->redirectToRoute('subscription_show', array('id' => $entity->getId()));
            }

        }

        return $this->render('subscription/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Subscription entity.
     *
     * @param Subscription $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Subscription $entity)
    {
        $form = $this->createForm('BackendBundle\Form\SubscriptionType', $entity, array(
            'action' => $this->generateUrl('subscription_new'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));
        $form->add('submitback', SubmitType::class, array('label' => 'Create & Back'));

        return $form;
    }

    /**
     * Finds and displays a Subscription entity.
     *
     */
    public function showAction(Subscription $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('subscription/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Subscription entity.
     *
     */
    public function editAction(Request $request, Subscription $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The subscription was updated succesfully.');
            return $this->redirectToRoute('subscription_show', array('id' => $entity->getId()));
        }

        return $this->render('subscription/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Subscription entity.
     *
     * @param Subscription $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Subscription $entity)
    {
        $form = $this->createForm('BackendBundle\Form\SubscriptionType', $entity, array(
            'action' => $this->generateUrl('subscription_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Subscription entity.
     *
     */
    public function deleteAction(Request $request, Subscription $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Subscription entity.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The subscription was deleted succesfully.');
        return $this->redirect($this->generateUrl('subscription'));


    }

    /**
     * Creates a form to delete a Subscription entity.
     *
     * @param Subscription $entity The Subscription entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Subscription $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('subscription_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Do several batch actions over Subscription entities.
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
                    $entity = $em->getRepository('BackendBundle:Subscription')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Subscription entity.');
                    } else {
                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Subscriptions deleted succesfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('subscription'));
    }
}
