<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Reward;
use BackendBundle\Form\RewardType;

/**
 * Reward controller.
 *
 */
class RewardController extends Controller
{
    /**
     * Lists all Reward entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:Reward e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), 5
        );

        return $this->render('reward/index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Find Reward entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');

        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:Reward e WHERE "
                . "e.name LIKE '%" . $find . "%' OR "
                . "e.description LIKE '%" . $find . "%' OR "
                . "e.cost LIKE '%" . $find . "%' "
                . "ORDER BY e.id ASC";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1), 5
            );

            return $this->render('reward/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('reward'));
        }
    }

    /**
     * Displays a form to create a new Reward entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Reward();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The reward was created succesfully.');

            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('reward_new'));
            } else {
                return $this->redirectToRoute('reward_show', array('id' => $entity->getId()));
            }

        }

        return $this->render('reward/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Reward entity.
     *
     * @param Reward $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Reward $entity)
    {
        $form = $this->createForm('BackendBundle\Form\RewardType', $entity, array(
            'action' => $this->generateUrl('reward_new'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));
        $form->add('submitback', SubmitType::class, array('label' => 'Create & Back'));

        return $form;
    }

    /**
     * Finds and displays a Reward entity.
     *
     */
    public function showAction(Reward $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('reward/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Reward entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \BackendBundle\Entity\Reward              $entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function editAction(Request $request, Reward $entity)
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
            $this->get('session')->getFlashBag()->add('success', 'The reward was updated succesfully.');
            return $this->redirectToRoute('reward_show', array('id' => $entity->getId()));
        }

        return $this->render('reward/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Reward entity.
     *
     * @param Reward $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Reward $entity)
    {
        $form = $this->createForm('BackendBundle\Form\RewardType', $entity, array(
            'action' => $this->generateUrl('reward_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Reward entity.
     *
     */
    public function deleteAction(Request $request, Reward $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reward entity.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The reward was deleted succesfully.');
        return $this->redirect($this->generateUrl('reward'));


    }

    /**
     * Creates a form to delete a Reward entity.
     *
     * @param Reward $entity The Reward entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Reward $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reward_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Do several batch actions over Reward entities.
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
                    $entity = $em->getRepository('BackendBundle:Reward')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Reward entity.');
                    } else {
                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Rewards deleted succesfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('reward'));
    }
}
