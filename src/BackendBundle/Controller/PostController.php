<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\PostImage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Post;
use BackendBundle\Form\PostType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Post controller.
 *
 */
class PostController extends Controller
{
    /**
     * Lists all Post entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:Post e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), 5
        );

        return $this->render('post/index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Find Post entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');

        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:Post e WHERE "
                . "e.id LIKE '%" . $find . "%' OR "
                . "e.id LIKE '%" . $find . "%' "
                . "ORDER BY e.id ASC";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1), 5
            );

            return $this->render('post/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('post'));
        }
    }

    /**
     * Displays a form to create a new Post entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Post();
        $entity->setAuthor($this->getUser());

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The post was created successfully.');

            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('post_new'));
            } else {
                return $this->redirectToRoute('post_show', array('id' => $entity->getId()));
            }

        }

        // Image functions
        $imageEntity = new PostImage();

        $imageForm = $this->createForm('BackendBundle\Form\PostImageType', $imageEntity, array(
            'action' => $this->generateUrl('post_upload_image'),
            'method' => 'POST',
        ));

        $postImages = $em->getRepository('BackendBundle:PostImage')->findAll();


        return $this->render('post/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'image_form' => $imageForm->createView(),
            'post_images' => $postImages,
        ));
    }

    /**
     * Creates a form to create a Post entity.
     *
     * @param Post $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Post $entity)
    {
        $form = $this->createForm('BackendBundle\Form\PostType', $entity, array(
            'action' => $this->generateUrl('post_new'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));
        $form->add('submitback', SubmitType::class, array('label' => 'Create & Back'));

        return $form;
    }

    /**
     * Finds and displays a Post entity.
     *
     */
    public function showAction(Post $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('post/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     */
    public function editAction(Request $request, Post $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The post was updated successfully.');
            return $this->redirectToRoute('post_show', array('id' => $entity->getId()));
        }

        // Image functions
        $imageEntity = new PostImage();

        $imageForm = $this->createForm('BackendBundle\Form\PostImageType', $imageEntity, array(
            'action' => $this->generateUrl('post_upload_image'),
            'method' => 'POST',
        ));

        $postImages = $em->getRepository('BackendBundle:PostImage')->findAll();

        return $this->render('post/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'image_form' => $imageForm->createView(),
            'post_images' => $postImages,
        ));
    }

    /**
     * Creates a form to edit a Post entity.
     *
     * @param Post $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Post $entity)
    {
        $form = $this->createForm('BackendBundle\Form\PostType', $entity, array(
            'action' => $this->generateUrl('post_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Post entity.
     *
     */
    public function deleteAction(Request $request, Post $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The post was deleted successfully.');
        return $this->redirect($this->generateUrl('post'));


    }

    /**
     * Creates a form to delete a Post entity.
     *
     * @param Post $entity The Post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Do several batch actions over Post entities.
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
                    $entity = $em->getRepository('BackendBundle:Post')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Post entity.');
                    } else {
                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Posts deleted successfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('post'));
    }

    public function uploadImageAction(Request $request)
    {
        $entity = new PostImage();

        $form = $this->createForm('BackendBundle\Form\PostImageType', $entity, array(
            'action' => $this->generateUrl('post_upload_image'),
            'method' => 'POST',
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $result = array(
                'id' => $entity->getId(),
                'imgName' => $entity->getImgName(),
                'description' => $entity->getDescription(),
                'createdAt' => $entity->getCreatedAt(),
                'updatedAt' => $entity->getUpdatedAt(),
            );

            return new Response(json_encode($result));
        }

        return new Response("Error", 500);
    }

    public function deleteImageAction(Request $request, PostImage $entity)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $id = array('id' => $entity->getId());

        $em->remove($entity);
        $em->flush();

        return new Response(json_encode($id));
    }
}
