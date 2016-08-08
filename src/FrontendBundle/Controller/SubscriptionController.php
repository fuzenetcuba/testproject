<?php

namespace FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;//------------
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Subscription;
use BackendBundle\Form\SubscriptionType;

/**
 * Subscription controller.
 *
 */
class SubscriptionController extends Controller
{
    /**
     * Displays a form to create a new Subscription entity.
     *
     */
    public function subscribeAction(Request $request)
    {
        $entity = new Subscription();
        $form = $this->createSubscribeForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('subscription_confirm', array('id' => $entity->getId()));
        }

        return $this->render('@Frontend/Subscription/subscribe.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Subscription entity.
     *
     * @param Subscription $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSubscribeForm(Subscription $entity)
    {
        $form = $this->createForm('BackendBundle\Form\SubscriptionType', $entity, array(
            'action' => $this->generateUrl('subscription_request'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Subscribe'));

        return $form;
    }

    /**
     * Finds and displays a Subscription entity.
     *
     */
    public function confirmAction(Subscription $entity)
    {
        return $this->render('@Frontend/Subscription/confirm.html.twig', array(
            'entity' => $entity,
        ));
    }

}
