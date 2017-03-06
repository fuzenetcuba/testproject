<?php

namespace BackendBundle\Controller;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Deal;
use BackendBundle\Form\DealType;

/**
 * Deal controller.
 *
 */
class DealController extends Controller
{
    /**
     * Lists all Deal entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:Deal e ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), $this->getParameter('cruds.pagination.items')
        );

        return $this->render('deal/index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Find Deal entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');

        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:Deal e WHERE "
                . "e.name LIKE '%" . $find . "%' OR "
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

            return $this->render('deal/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('deal'));
        }
    }

    /**
     * Displays a form to create a new Deal entity.
     *
     */
    public function newAction(Request $request)
    {
        $entity = new Deal();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The deal was created succesfully.');

            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('deal_new'));
            } else {
                return $this->redirectToRoute('deal_show', array('id' => $entity->getId()));
            }

        }

        return $this->render('deal/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Deal entity.
     *
     * @param Deal $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Deal $entity)
    {
        $form = $this->createForm('BackendBundle\Form\DealType', $entity, array(
            'action' => $this->generateUrl('deal_new'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));
        $form->add('submitback', SubmitType::class, array('label' => 'Create & Back'));

        return $form;
    }

    /**
     * Finds and displays a Deal entity.
     *
     */
    public function showAction(Deal $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('deal/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Deal entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \BackendBundle\Entity\Deal                $entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function editAction(Request $request, Deal $entity)
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
            $this->get('session')->getFlashBag()->add('success', 'The deal was updated succesfully.');
            return $this->redirectToRoute('deal_show', array('id' => $entity->getId()));
        }

        return $this->render('deal/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Deal entity.
     *
     * @param Deal $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Deal $entity)
    {
        $form = $this->createForm('BackendBundle\Form\DealType', $entity, array(
            'action' => $this->generateUrl('deal_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Deal entity.
     *
     */
    public function deleteAction(Request $request, Deal $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Deal entity.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The deal was deleted succesfully.');
        return $this->redirect($this->generateUrl('deal'));


    }

    /**
     * Creates a form to delete a Deal entity.
     *
     * @param Deal $entity The Deal entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Deal $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('deal_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Do several batch actions over Deal entities.
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
                    $entity = $em->getRepository('BackendBundle:Deal')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Deal entity.');
                    } else {
                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Deals deleted succesfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('deal'));
    }

    /**
     * @param Request $request
     *
     * @param         $id
     *
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function toggleAction(Request $request, $id)
    {
        /** @var Deal $deal */
        $deal = $this->get('deal.manager')->find($id);

        $deal->toggle();
        $this->get('deal.manager')->save($deal);

        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse('ok');
    }
}
