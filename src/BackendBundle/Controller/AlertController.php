<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Alert;
use BackendBundle\Form\AlertType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Alert controller.
 *
 */
class AlertController extends Controller
{
    /**
     * Lists all Alert entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT e FROM BackendBundle:Alert e ORDER BY e.date DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), 5
        );

        return $this->render('alert/index.html.twig', array(
            'entities' => $pagination,
            'older_than' => $this->getParameter('alert.antiquity.months')
        ));
    }

    /**
     * Find Alert entities that match with the criteria.
     *
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');

        if ($find) {
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT e FROM BackendBundle:Alert e WHERE "
                . "e.message LIKE '%" . $find . "%' OR "
                . "e.url LIKE '%" . $find . "%' "
                . "ORDER BY e.date DESC";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, $request->query->get('page', 1), 5
            );

            return $this->render('alert/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find,
                'older_than' => $this->getParameter('alert.antiquity.months')
            ));
        } else {
            return $this->redirect($this->generateUrl('alert'));
        }
    }

    /**
     * Finds and displays a Alert entity.
     *
     */
    public function showAction(Alert $entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        $this->checkAlert($entity);

        return $this->render('alert/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Show details of entity that dispatch the Alert.
     *
     */
    public function detailsAction(Alert $entity)
    {
        $this->checkAlert($entity);

        return $this->redirect($this->get('request')->getUriForPath($entity->getUrl()));
    }


    /**
     * Check the Alert entity
     *
     */
    private function checkAlert(Alert $entity)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alert entity.');
        }

        $entity->setChecked(true);

        $em->persist($entity);
        $em->flush();
    }

    /**
     * Deletes a Alert entity.
     *
     */
    public function deleteAction(Request $request, Alert $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alert entity.');
        }

        $em->remove($entity);
        $em->flush();

        // Mostrando mensaje
        $this->get('session')->getFlashBag()->add('success', 'The alert was deleted successfully.');
        return $this->redirect($this->generateUrl('alert'));

    }

    /**
     * Creates a form to delete a Alert entity.
     *
     * @param Alert $entity The Alert entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Alert $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('alert_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Change the checked state of an alert
     *
     * @param Request $request Request
     * @param string $id ID of Alert
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function checkAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Alert')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alert entity.');
        }

        $status = $entity->getChecked();
        $entity->setChecked(!$status);
        $em->persist($entity);
        $em->flush();

//            $entityId = $entity->getId();
//
//            $response = '<div class="onoffswitch-ios switch-xs" onclick="userEnabled(' . $entityId . ');">'
//                . '<label class="onoffswitch-ios-label ' . ($status ? '' : 'checked') . '" for="switch-ios-' . $entityId . '"></label>'
//                . '</div>';
//
        return new Response(""/*$response*/);
    }

    /**
     * Do several batch actions over Alert entities.
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
                    $entity = $em->getRepository('BackendBundle:Alert')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Alert entity.');
                    } else {
                        $em->remove($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Alerts deleted successfully.');
                }
            } elseif ($action == "enable") {
                foreach ($ids as $id) {
                    $entity = $em->getRepository('BackendBundle:Alert')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Alert entity.');
                    } else {
                        $entity->setChecked(true);
                        $em->persist($entity);
                        $recordsSelected = true;
                    }
                }

                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Alerts checked successfully.');
                }
            } elseif ($action == "disable") {
                foreach ($ids as $id) {
                    $entity = $em->getRepository('BackendBundle:Alert')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find Alert entity.');
                    } else {
                        $entity->setChecked(false);
                        $em->persist($entity);
                        $recordsSelected = true;
                    }
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Alerts unchecked successfully.');
                }
            }
            $em->flush();
        }


        return $this->redirect($this->generateUrl('alert'));
    }

    /**
     * Remove all alert with more antiquity than #month
     */
    public function deleteOlderAction()
    {
        $am = $this->get('alert.manager');
        $olderMonths = $this->getParameter('alert.antiquity.months');
        $am->deleteAlertsOlderThan($olderMonths);

        $this->get('session')->getFlashBag()->add('success', 'Alerts deleted successfully.');

        return $this->redirect($this->generateUrl('alert'));
    }

    /**
     * Get unchecked alerts.
     *
     */
    public function getNotificationUncheckedAlertsAction()
    {
        $em = $this->get('alert.manager');
        $cant = $this->getParameter('alert.max.notification.inbox');
        $alerts = $em->findAlertsUnchecked($cant);

        return $this->render('BackendBundle:Layout:alert_notifications.html.twig', array(
            'alerts' => $alerts
        ));
    }

    /**
     * Count unchecked alerts.
     *
     */
    public function countUncheckedAlertsAction()
    {
        $em = $this->get('alert.manager');
        $alerts = $em->countAlertsUnchecked();

        return new Response($alerts);
    }
}
