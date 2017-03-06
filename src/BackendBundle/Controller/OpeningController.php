<?php

namespace BackendBundle\Controller;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Opening;
use BackendBundle\Form\OpeningType;

/**
 * Opening controller.
 *
 */
class OpeningController extends Controller
{
    /**
     * Lists all Opening entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:Opening e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), $this->getParameter('cruds.pagination.items')
        );

        return $this->render('opening/index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Find Opening entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');

        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:Opening e "
                . "WHERE "
                . "e.position LIKE '%" . $find . "%' OR "
                . "e.department LIKE '%" . $find . "%' OR "
                . "e.description LIKE '%" . $find . "%' "
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

            return $this->render('opening/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('opening'));
        }
    }

    /**
     * Displays a form to create a new Opening entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Opening();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The opening was created succesfully.');

            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('opening_new'));
            } else {
                return $this->redirectToRoute('opening_show', array('id' => $entity->getId()));
            }

        }

        return $this->render('opening/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Opening entity.
     *
     * @param Opening $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Opening $entity)
    {
        $form = $this->createForm('BackendBundle\Form\OpeningType', $entity, array(
            'action' => $this->generateUrl('opening_new'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));
        $form->add('submitback', SubmitType::class, array('label' => 'Create & Back'));

        return $form;
    }

    /**
     * Finds and displays a Opening entity.
     *
     */
    public function showAction(Opening $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('opening/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Opening entity.
     *
     */
    public function editAction(Request $request, Opening $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The opening was updated succesfully.');
            return $this->redirectToRoute('opening_show', array('id' => $entity->getId()));
        }

        return $this->render('opening/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Opening entity.
     *
     * @param Opening $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Opening $entity)
    {
        $form = $this->createForm('BackendBundle\Form\OpeningType', $entity, array(
            'action' => $this->generateUrl('opening_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Opening entity.
     *
     */
    public function deleteAction(Request $request, Opening $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Opening entity.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The opening was deleted succesfully.');
        return $this->redirect($this->generateUrl('opening'));


    }

    /**
     * Creates a form to delete a Opening entity.
     *
     * @param Opening $entity The Opening entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Opening $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('opening_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Do several batch actions over Opening entities.
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
                    $entity = $em->getRepository('BackendBundle:Opening')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Opening entity.');
                    } else {
                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Openings deleted succesfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('opening'));
    }
}
