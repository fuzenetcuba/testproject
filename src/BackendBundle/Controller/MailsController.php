<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class MailsController extends Controller
{
    public function sendAction(Request $request)
    {
        $form = $this->createForm('BackendBundle\Form\MailsType', null, array(
            'action' => $this->generateUrl('mails_send'),
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataRequest = $request->request->get("frontend_bundle_mails_send");
            if (!isset($dataRequest['registeredUsers']) &&
                !isset($dataRequest['subscribedUsers']) &&
                strlen($dataRequest['customAddresses']) === 0 &&
                strlen($dataRequest['groupOfUsers']) === 0 &&
                !isset($dataRequest['categories'])
            ) {
                $this->get('session')->getFlashBag()->add('danger', 'You must choice one recipient address at least.');
            } else {
                echo var_dump($dataRequest);
                die;
//                $em = $this->getDoctrine()->getManager();
//                $em->persist($entity);
//                $em->flush();

                // Mostrando mensaje
                $this->get('session')->getFlashBag()->add('success', 'The mail was sent successfully.');

                return $this->redirect($this->generateUrl('mails_send'));
            }
        }

        return $this->render('mails/send.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
