<?php

namespace FrontendBundle\Controller;

use BackendBundle\Entity\Subscription;
use BackendBundle\Form\SubscriptionType;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 * Subscription controller.
 *
 */
class SubscriptionController extends Controller
{
    const USER_SUBSCRIBED = 'user.subscribed';
    const USER_DESUBSCRIBED = 'user.desubscribed';
    const USER_RESUBSCRIBED = 'user.resubscribed';

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
            
            // notify a custom event to handle a new subscribed user
            $this->get('event_dispatcher')->dispatch(self::USER_SUBSCRIBED, new FormEvent($form, $request));

            return $this->redirectToRoute('subscription_confirm', array('id' => $entity->getId(), 'option' => 'subscribe'));
        }

        return $this->render('@Frontend/Subscription/subscribe.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'option' => 'subscribe',
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

        $form->add('submit', SubmitType::class, array('label' => 'SUBSCRIBE'));

        return $form;
    }

    /**
     * Displays a form to create a new Subscription entity.
     *
     */
    public function desubscribeAction(Request $request)
    {
        $form = $this->createDesubscribeForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataRequest = $request->request->get("form");
            $data = $form->getData();

            if (isset($dataRequest['email'])) {

                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('BackendBundle:Subscription')->findOneBy(['email' => $data['email']]);

                if (!$entity) {
                    $form->get('email')->addError(new FormError('Does not exist a subscription with with email'));
                } else {
                    $entity->setSubscribed(false);
                    $em->persist($entity);
                    $em->flush();

                    // notify a custom event to handle a new subscribed user
                    $this->get('event_dispatcher')->dispatch(self::USER_DESUBSCRIBED, new FormEvent($form, $request));

                    return $this->redirectToRoute('subscription_confirm', array('id' => $entity->getId(), 'option' => 'desubscribe'));
                }
            }
        }

        return $this->render('@Frontend/Subscription/subscribe.html.twig', array(
            'form' => $form->createView(),
            'option' => 'desubscribe',
        ));
    }

    /**
     * Creates a form to create a Subscription entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDesubscribeForm()
    {
        $form = $this->createFormBuilder(null, array(
            'action' => $this->generateUrl('subscription_cancel'),
            'method' => 'POST',
        ))->getForm();

        $form
            ->add('email', EmailType::class)
            ->add('submit', SubmitType::class, array('label' => 'DESUBSCRIBE', 'translation_domain' => 'subscription'));

        return $form;
    }

    /**
     * Displays a form to create a new Subscription entity.
     *
     */
    public function resubscribeAction(Request $request)
    {
        $form = $this->createResubscribeForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataRequest = $request->request->get("form");
            $data = $form->getData();

            if (isset($dataRequest['email'])) {

                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('BackendBundle:Subscription')->findOneBy(['email' => $data['email']]);

                if (!$entity) {
                    $form->get('email')->addError(new FormError('Does not exist a subscription with with email'));
                } else {
                    $entity->setSubscribed(true);
                    $entity->setCategories($data['categories']);
                    $em->persist($entity);
                    $em->flush();

                    // notify a custom event to handle a new subscribed user
                    $this->get('event_dispatcher')->dispatch(self::USER_RESUBSCRIBED, new FormEvent($form, $request));

                    return $this->redirectToRoute('subscription_confirm', array('id' => $entity->getId(), 'option' => 'resubscribe'));
                }
            }
        }

        return $this->render('@Frontend/Subscription/subscribe.html.twig', array(
            'form' => $form->createView(),
            'option' => 'resubscribe',
        ));
    }

    /**
     * Creates a form to create a Subscription entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createResubscribeForm()
    {
        $form = $this->createFormBuilder(null, array(
            'action' => $this->generateUrl('subscription_resubscribe'),
            'method' => 'POST',
        ))->getForm();

        $form
            ->add('email', EmailType::class)
            ->add('categories', EntityType::class, [
                'class' => 'BackendBundle\Entity\Category',
                'multiple' => true,
                'empty_value' => 'SELECT A CATEGORY'
            ])
            ->add('submit', SubmitType::class, array('label' => 'RESUBSCRIBE', 'translation_domain' => 'subscription'));

        return $form;
    }

    /**
     * Finds and displays a Subscription entity.
     *
     */
    public function confirmAction(Subscription $entity, $option)
    {
        return $this->render('@Frontend/Subscription/confirm.html.twig', array(
            'entity' => $entity,
            'option' => $option
        ));
    }

}
