<?php

namespace BackendBundle\Controller;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\OpeningCategory;
use BackendBundle\Form\OpeningCategoryType;

/**
 * Category controller.
 *
 */
class OpeningCategoryController extends Controller
{
    /**
     * Lists all OpeningCategory entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:OpeningCategory e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), $this->getParameter('cruds.pagination.items')
        );

        return $this->render('openingcategory/index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Find OpeningCategory entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');

        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:OpeningCategory e WHERE "
                . "e.name LIKE '%" . $find . "%' OR "
                . "e.slug LIKE '%" . $find . "%' OR "
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

            return $this->render('openingcategory/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('opening_category'));
        }
    }

    /**
     * Displays a form to create a new OpeningCategory entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new OpeningCategory();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The opening category was created successfully.');

            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('opening_category_new'));
            } else {
                return $this->redirectToRoute('opening_category_show', array('id' => $entity->getId()));
            }

        }

        return $this->render('openingcategory/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a OpeningCategory entity.
     *
     * @param OpeningCategory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(OpeningCategory $entity)
    {
        $form = $this->createForm('BackendBundle\Form\OpeningCategoryType', $entity, array(
            'action' => $this->generateUrl('opening_category_new'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));
        $form->add('submitback', SubmitType::class, array('label' => 'Create & Back'));

        return $form;
    }

    /**
     * Finds and displays a OpeningCategory entity.
     *
     */
    public function showAction(OpeningCategory $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('openingcategory/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing OpeningCategory entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \BackendBundle\Entity\OpeningCategory            $entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function editAction(Request $request, OpeningCategory $entity)
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
            $this->get('session')->getFlashBag()->add('success', 'The opening category was updated succesfully.');
            return $this->redirectToRoute('opening_category_show', array('id' => $entity->getId()));
        }

        return $this->render('openingcategory/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a OpeningCategory entity.
     *
     * @param OpeningCategory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(OpeningCategory $entity)
    {
        $form = $this->createForm('BackendBundle\Form\OpeningCategoryType', $entity, array(
            'action' => $this->generateUrl('opening_category_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a OpeningCategory entity.
     *
     */
    public function deleteAction(Request $request, OpeningCategory $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Opening Category entity.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The opening category was deleted succesfully.');
        return $this->redirect($this->generateUrl('opening_category'));


    }

    /**
     * Creates a form to delete a OpeningCategory entity.
     *
     * @param OpeningCategory $entity The OpeningCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(OpeningCategory $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('opening_category_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Do several batch actions over OpeningCategory entities.
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
                    $entity = $em->getRepository('BackendBundle:OpeningCategory')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Opening Category entity.');
                    } else {
                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Opening categories deleted succesfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('opening_category'));
    }
}
