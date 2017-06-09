<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\PressPost;
use BackendBundle\Form\PressPostType;

/**
 * PressPost controller.
 *
 */
class PressPostController extends Controller
{
    /**
     * Lists all PressPost entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:PressPost e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1), 5
        );

    return $this->render('presspost/index.html.twig', array(
        'entities' => $pagination,
    ));
    }

    /**
     * Find PressPost entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');
        
        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:PressPost e WHERE "
                    . "e.id LIKE '%" . $find . "%' OR "
                    . "e.id LIKE '%" . $find . "%' "
                    . "ORDER BY e.id ASC";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $request->query->get('page', 1), 10
            );

            return $this->render('presspost/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('press'));
        }
    }

    /**
     * Displays a form to create a new PressPost entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new PressPost();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The presspost was created successfully.');

            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('press_new'));
            } else {
            return $this->redirectToRoute('press_show', array('id' => $entity->getId()));            }

        }

    return $this->render('presspost/new.html.twig', array(
        'entity' => $entity,
        'form' => $form->createView(),
    ));
    }

            /**
        * Creates a form to create a PressPost entity.
        *
        * @param PressPost $entity The entity
        *
        * @return \Symfony\Component\Form\Form The form
        */
        private function createCreateForm(PressPost $entity)
        {
            $form = $this->createForm('BackendBundle\Form\PressPostType', $entity, array(
                'action' => $this->generateUrl('press_new'),
                'method' => 'POST',
            ));

            $form->add('submit', SubmitType::class, array('label' => 'Create'));
            $form->add('submitback', SubmitType::class, array('label' => 'Create & Back'));

            return $form;
        }
    
    /**
     * Finds and displays a PressPost entity.
     *
     */
    public function showAction(PressPost $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('presspost/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PressPost entity.
     *
     */
    public function editAction(Request $request, PressPost $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
    
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The presspost was updated successfully.');
            return $this->redirectToRoute('press_show', array('id' => $entity->getId()));
        }

    return $this->render('presspost/edit.html.twig', array(
        'entity' => $entity,
        'form' => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
    ));
    }

    /**
    * Creates a form to edit a PressPost entity.
    *
    * @param PressPost $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PressPost $entity)
    {
        $form = $this->createForm('BackendBundle\Form\PressPostType', $entity, array(
            'action' => $this->generateUrl('press_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a PressPost entity.
     *
     */
    public function deleteAction(Request $request, PressPost $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PressPost entity.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The presspost was deleted successfully.');
        return $this->redirect($this->generateUrl('press'));



    }

    /**
    * Creates a form to delete a PressPost entity.
    *
    * @param PressPost $entity The PressPost entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createDeleteForm(PressPost $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('press_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    /**
     * Do several batch actions over PressPost entities.
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
                    $entity = $em->getRepository('BackendBundle:PressPost')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find PressPost entity.');
                    } else {
                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'PressPosts deleted successfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('press'));
    }
}
