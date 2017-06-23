<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\PostImage;
use BackendBundle\Form\PostImageType;

/**
 * PostImage controller.
 *
 */
class PostImageController extends Controller
{
    /**
     * Lists all PostImage entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:PostImage e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1), $request->query->getInt('limit', $this->getParameter('cruds.pagination.items'))
        );

    return $this->render('postimage/index.html.twig', array(
        'entities' => $pagination,
    ));
    }

    /**
     * Find PostImage entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');
        
        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:PostImage e WHERE "
                    . "e.imgName LIKE '%" . $find . "%' "
                    . "ORDER BY e.id ASC";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $request->query->get('page', 1), $request->query->getInt('limit', $this->getParameter('cruds.pagination.items'))
            );

            return $this->render('postimage/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('postimage'));
        }
    }

    /**
     * Displays a form to create a new PostImage entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new PostImage();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The postimage was created successfully.');

            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('postimage_new'));
            } else {
            return $this->redirectToRoute('postimage_show', array('id' => $entity->getId()));            }

        }

    return $this->render('postimage/new.html.twig', array(
        'entity' => $entity,
        'form' => $form->createView(),
    ));
    }

            /**
        * Creates a form to create a PostImage entity.
        *
        * @param PostImage $entity The entity
        *
        * @return \Symfony\Component\Form\Form The form
        */
        private function createCreateForm(PostImage $entity)
        {
            $form = $this->createForm('BackendBundle\Form\PostImageType', $entity, array(
                'action' => $this->generateUrl('postimage_new'),
                'method' => 'POST',
            ));

            $form->add('submit', SubmitType::class, array('label' => 'Create'));
            $form->add('submitback', SubmitType::class, array('label' => 'Create & Back'));

            return $form;
        }
    
    /**
     * Finds and displays a PostImage entity.
     *
     */
    public function showAction(PostImage $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('postimage/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PostImage entity.
     *
     */
    public function editAction(Request $request, PostImage $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
    
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The postimage was updated successfully.');
            return $this->redirectToRoute('postimage_show', array('id' => $entity->getId()));
        }

    return $this->render('postimage/edit.html.twig', array(
        'entity' => $entity,
        'edit_form' => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
    ));
    }

    /**
    * Creates a form to edit a PostImage entity.
    *
    * @param PostImage $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PostImage $entity)
    {
        $form = $this->createForm('BackendBundle\Form\PostImageType', $entity, array(
            'action' => $this->generateUrl('postimage_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a PostImage entity.
     *
     */
    public function deleteAction(Request $request, PostImage $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PostImage entity.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The postimage was deleted successfully.');
        return $this->redirect($this->generateUrl('postimage'));



    }

    /**
    * Creates a form to delete a PostImage entity.
    *
    * @param PostImage $entity The PostImage entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createDeleteForm(PostImage $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('postimage_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    /**
     * Do several batch actions over PostImage entities.
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
                    $entity = $em->getRepository('BackendBundle:PostImage')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find PostImage entity.');
                    } else {
                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'PostImages deleted successfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('postimage'));
    }
}
