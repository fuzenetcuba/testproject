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
        $form = $this->createSubscribeForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dataRequest = $request->request->get("form");
            $data = $form->getData();

            if (isset($dataRequest['email'])) {

                $em = $this->getDoctrine()->getManager();
                $subscription = $em->getRepository('BackendBundle:Subscription')->findOneBy(['email' => $data['email']]);

                $isNew = false;
                if (!$subscription) {
                    $subscription = new Subscription();
                    $isNew = true;
                }

                // if there is a user with this email, mark it as subscribed
                $user = $em->getRepository('BackendBundle:SystemUser')->findOneBy(['email' => $data['email']]);
                if (!!$user) {
                    $user->setSubscribed(true);
                    $em->persist($user);
                }

                $subscription->setEmail($data['email']);
                $subscription->setSubscribed(true);
                $subscription->setCategories($data['categories']);
                $em->persist($subscription);
                $em->flush();

                if ($isNew) {

                    // notify a custom event to handle a new subscribed user
                    $this->get('event_dispatcher')->dispatch(self::USER_SUBSCRIBED, new FormEvent($form, $request));

                    return $this->redirectToRoute('subscription_confirm', array('id' => $subscription->getId(), 'option' => 'subscribe'));

                } else {

                    // notify a custom event to handle an update of subscribed user
                    $this->get('event_dispatcher')->dispatch(self::USER_RESUBSCRIBED, new FormEvent($form, $request));

                    return $this->redirectToRoute('subscription_confirm', array('id' => $subscription->getId(), 'option' => 'resubscribe'));

                }
            }
        }

        return $this->render('@Frontend/Subscription/subscribe.html.twig', array(
            'form' => $form->createView(),
            'option' => 'subscribe',
        ));
    }

    /**
     * Creates a form to create a Subscription entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSubscribeForm()
    {
        $form = $this->createFormBuilder(null, array(
            'action' => $this->generateUrl('subscription_request'),
            'method' => 'POST',
        ))->getForm();

        $form
            ->add('email', EmailType::class)
            ->add('categories', EntityType::class, [
                'class' => 'BackendBundle\Entity\Category',
                'multiple' => true,
                'empty_value' => 'SELECT A CATEGORY'
            ])
            ->add('submit', SubmitType::class, array('label' => 'SUBSCRIBE', 'translation_domain' => 'subscription'));

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
                $subscription = $em->getRepository('BackendBundle:Subscription')->findOneBy(['email' => $data['email']]);
                $user = $em->getRepository('BackendBundle:SystemUser')->findOneBy(['email' => $data['email']]);

                if (!$subscription && !$user) {
                    $error = true;      //  if there is no any of both subscription types, launch ERROR
                } else {
                    $error = false;     //  else means there is one kind of subscription

                    if (!$user) {       //  if the subscription is not a USER, then is a SUBSCRIPTION
                        $subscription->setSubscribed(false);
                        $em->persist($subscription);
                    } elseif (!$subscription) {             //  if the subscription is not a subscription, then is a SUBSCRIPTION
                        $user->setSubscribed(false);
                        $em->persist($user);
                    } else {                                //  else means the subscription is a USER and a SUBSCRIPTION
                        $subscription->setSubscribed(false);
                        $user->setSubscribed(false);
                        $em->persist($subscription);
                        $em->persist($user);
                    }

                    $em->flush();       //  storing changes on BD
                }

                if ($error) {
                    $form->get('email')->addError(new FormError('Does not exist a subscription with with email'));
                } else {
                    // notify a custom event to handle a new subscribed user
//                    $this->get('event_dispatcher')->dispatch(self::USER_DESUBSCRIBED, new FormEvent($form, $request));

                    return $this->redirectToRoute('subscription_confirm', array('id' => (!!$subscription ? $subscription->getId() : null), 'option' => 'desubscribe'));
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
            ->add('submit', SubmitType::class, array('label' => 'CANCEL SUBSCRIPTION', 'translation_domain' => 'subscription'));

        return $form;
    }

    /**
     * Finds and displays a Subscription entity.
     *
     */
    public function confirmAction($id, $option)
    {
        if ($id !== null) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendBundle:Subscription')->findOneBy(['id' => $id]);
        } else {
            $entity = null;
        }

        return $this->render('@Frontend/Subscription/confirm.html.twig', array(
            'entity' => $entity,
            'option' => $option
        ));
    }

}
