<?php

namespace BackendBundle\Controller;

use BackendBundle\Form\customerType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BackendBundle\Entity\SystemUser;
use \Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{

    /**
     * Muestra la lista de Usuarios registrados en el sistema
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT e FROM BackendBundle:SystemUser e "
            . " WHERE e.roles LIKE '%ROLE_CUSTOMER%' "
            . " ORDER BY e.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->get('page', 1), 5
        );

        return $this->render('customer/index.html.twig', array(
                'entities' => $pagination)
        );
    }

    /**
     * Busca en la lista de Usuarios aquellos que cumplan con una busqueda determinada
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function findAction(Request $request)
    {
        $find = $request->get('find-form-text');

        if ($find) {
            $em = $this->getDoctrine()->getManager();
            $dql = "SELECT e FROM BackendBundle:SystemUser e "
                . " WHERE e.roles LIKE '%ROLE_CUSTOMER%' AND "
                . " (e.username LIKE '%" . $find . "%' OR "
                . " e.firstName LIKE '%" . $find . "%' OR "
                . " e.lastName LIKE '%" . $find . "%' OR "
                . " e.phone LIKE '%" . $find . "%' OR "
                . " e.email LIKE '%" . $find . "%') "
                . " ORDER BY e.id ASC";
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1), 5
            );

            return $this->render('customer/index.html.twig', array(
                'entities' => $pagination,
                'textFind' => $find
            ));
        } else {
            return $this->redirect($this->generateUrl('customer'));
        }
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param SystemUser $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SystemUser $entity)
    {
        $form = $this->createForm(new customerType(), $entity, array(
            'action' => $this->generateUrl('customer_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));
        $form->add('submitback', 'submit', array('label' => 'Create & Back'));

        return $form;
    }

    /**
     * Muestra la vista de Crear Usuario
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {

        $entity = new SystemUser();
        $entity->addRole('ROLE_USER');
        $form = $this->createCreateForm($entity);

        return $this->render('customer/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Crea un nuevo Usuario
     *
     * @param Request $request Datos de la solicitud
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new SystemUser();
        $entity->addRole('ROLE_CUSTOMER');
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($entity);
        $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        if (!preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $entity->getPassword())) {
            $form->get('password')->get('Password')->addError(new FormError('The password is invalid'));
            $this->get('session')->getFlashBag()->add('warning', 'The password must be contain:'
                . '<ul>'
                . '<li>At least one upper letter.</li>'
                . '<li>At least one lower letter.</li>'
                . '<li>At least one number or special character.</li>'
                . '<li>At least 8 characters as minimum.</li>'
                . '</ul>');
        } elseif ($form->isValid()) {
            $entity->setPassword($password);

            $em->persist($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The user was created succesfully.');
            if ($form->get('submitback')->isClicked()) {
                return $this->redirect($this->generateUrl('customer_new'));
            } else {
                return $this->redirect($this->generateUrl('customer_show', array('id' => $entity->getId())));
            }
        }

        return $this->render('customer/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    /**
     * Mostra la vista de Ver Datos de Usuario
     *
     * @param string $id ID del Usuario
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:SystemUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SystemUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);


        return $this->render('customer/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Muestra la vista de Editar Datos de Usuario
     *
     * @param string $id ID del Usuario
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:SystemUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SystemUser entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('customer/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param SystemUser $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(SystemUser $entity)
    {
        $form = $this->createForm(new customerType(), $entity, array(
            'action' => $this->generateUrl('customer_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Actualiza los datos de un Usuario
     *
     * @param Request $request Datos de la solicitud
     * @param string $id ID del Usuario
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:SystemUser')->find($id);
        $lastPassword = $entity->getPassword();
        $lastSalt = $entity->getSalt();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SystemUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($entity);
        $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());

        // verifying the user was not disabled

        if ($entity->getId() === $this->getUser()->getId() && !$entity->isEnabled()) {
            $entity->setEnabled(true);
            $this->get('session')->getFlashBag()->add('warning', 'You can\'t disable your own user.');
        } else {
            if (!preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $entity->getPassword()) && $entity->getPassword() != "") {
                $this->get('session')->getFlashBag()->add('warning', 'The password must be contain:'
                    . '<ul>'
                    . '<li>At least one upper letter.</li>'
                    . '<li>At least one lower letter.</li>'
                    . '<li>At least one number or special character.</li>'
                    . '<li>At least 8 characters as minimum.</li>'
                    . '</ul>');
            } elseif ($editForm->isValid()) {
                if ($editForm->get('password')->getData() == "") {
                    $password = $lastPassword;
                }
                $entity->setPassword($password);


                $em->persist($entity);
                $em->flush();

                // Mostrando mensaje
                $this->get('session')->getFlashBag()->add('success', 'The user was updated succesfully.');
                return $this->redirect($this->generateUrl('customer_show', array('id' => $id)));
            }
        }

        return $this->render('customer/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Elimina un Usuario registrado en el sistema
     *
     * @param Request $request Datos de la solicitud
     * @param string $id ID del Usuario
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        $userId = $request->get('user-id');

        if (/* $form->isValid() || */
        $userId
        ) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:SystemUser')->find($userId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SystemUser entity.');
            }

            $em->remove($entity);
            $em->flush();

            // Mostrando mensaje
            $this->get('session')->getFlashBag()->add('success', 'The user was deleted succesfully.');
            return $this->redirect($this->generateUrl('customer'));
        }

        $this->get('session')->getFlashBag()->add('danger', 'There was an error, verify the data please.');
        return $this->redirect($this->generateUrl('customer_show', array("id" => $id)));
    }

    /**
     * Crea el formulario de eliminar Etiqueta
     *
     * @param String $id ID de la Etiqueta
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('customer_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * Cambia el estado del usuario
     *
     * @param Request $request Datos de la solicitud
     * @param string $id ID del Usuario
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function enableAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:SystemUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SystemUser entity.');
        } elseif ($entity->getId() != $this->getUser()->getId()) {

            $status = $entity->isEnabled();
            $entity->setEnabled(!$status);
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
    }

    public function batchAction(Request $request)
    {
        $action = $request->get('batch_action_do');
        $ids = $request->get('batch_action_checkbox');
        $currentUser = false;
        $recordsSelected = false;

        if ($ids) {
            $em = $this->getDoctrine()->getManager();

            if ($action == "delete") {
                foreach ($ids as $id) {
                    $entity = $em->getRepository('BackendBundle:SystemUser')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find SystemUser entity.');
                    } else {
                        if ($entity->getId() != $this->getUser()->getId()) {

                            $em->remove($entity);
                            $recordsSelected = true;

                        } else {
                            $currentUser = true;
                        }
                    }
                }
                if ($currentUser) {
                    $this->get('session')->getFlashBag()->add('warning', 'You can\'t delete your own user.');
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Users deleted succesfully.');
                }
            } elseif ($action == "enable") {
                foreach ($ids as $id) {
                    $entity = $em->getRepository('BackendBundle:SystemUser')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find SystemUser entity.');
                    } else {
                        $entity->setEnabled(true);
                        $em->persist($entity);
                        $recordsSelected = true;
                    }
                }

                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Users enabled succesfully.');
                }
            } elseif ($action == "disable") {
                foreach ($ids as $id) {
                    $entity = $em->getRepository('BackendBundle:SystemUser')->find($id);

                    if (!$entity) {
                        throw $this->createNotFoundException('Unable to find SystemUser entity.');
                    } else {
                        if ($entity->getId() != $this->getUser()->getId()) {
                            $entity->setEnabled(false);
                            $em->persist($entity);
                            $recordsSelected = true;
                        } else {
                            $currentUser = true;
                        }
                    }
                }
                if ($currentUser) {
                    $this->get('session')->getFlashBag()->add('warning', 'You can\'t disable your own user.');
                }
                if ($recordsSelected) {
                    $this->get('session')->getFlashBag()->add('success', 'Users disabled succesfully.');
                }
            }
            $em->flush();
        }
        return $this->redirect($this->generateUrl('customer'));
    }

}
