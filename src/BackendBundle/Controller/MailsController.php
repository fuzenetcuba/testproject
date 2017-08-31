<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\PostImage;
use BackendBundle\Model\EmailGroups;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BackendBundle\Form\PostImageType;

class MailsController extends Controller
{
    public function sendAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('BackendBundle\Form\MailsType', null, array(
            'action' => $this->generateUrl('mails_send'),
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        // Image functions
        $imageEntity = new PostImage();

        $imageForm = $this->createForm(PostImageType::class, $imageEntity, array(
            'action' => $this->generateUrl('post_upload_image'),
            'method' => 'POST',
        ));

        $postImages = $em->getRepository('BackendBundle:PostImage')->findAll();

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
                $data = $form->getData();

                if (EmailGroups::REGISTERED_USERS === $data['groupOfUsers']) {

                    // ignore the registered users email, they will be duplicated
                    $users = $data['subscribedUsers']->toArray();
                    $users = array_merge($users, $this->get('customer.manager')->findAllSubscribed());

                } elseif (EmailGroups::SUBSCRIBED_USERS === $data['groupOfUsers']) {

                    // ignore the subscribed users
                    $users = $data['registeredUsers']->toArray();
                    $users = array_merge($users, $this->get('customer.manager')->findAllSubscribedByEmail());

                } elseif (EmailGroups::ALL_USERS === $data['groupOfUsers']) {

                    // ignore both
                    $users = $this->get('customer.manager')->findAllSubscribed();
                    $users = array_merge($users, $this->get('customer.manager')->findAllSubscribedByEmail());
                } else {

                    // left blank, collect both fields and deduplicate
                    $users = $data['registeredUsers']->toArray();
                    $users = array_merge($users, $data['subscribedUsers']->toArray());

                }

                $users = new ArrayCollection(array_unique($users));

                $customEmails = new ArrayCollection($data['customAddresses']);

                $users->map(function ($user) use ($customEmails) {
                    if ($customEmails->contains($user->getEmail())) {
                        $customEmails->removeElement($user->getEmail());
                    }
                });

                $categoryUsers = new ArrayCollection();

                $data['categories']->map(function ($category) use ($categoryUsers) {
                    array_map(function ($email) use ($categoryUsers) {
                        $categoryUsers->add($email);
                    }, array_column($this->get('subscription.manager')->findSubscribedsInCategory($category), 'email'));
                });

                $customEmails = new ArrayCollection(
                    array_unique(
                        array_merge(
                            $customEmails->toArray(),
                            $categoryUsers->toArray()
                        )
                    )
                );

                if (!isset($dataRequest['template'])) {
                    $content = $this->renderView('@Backend/Emails/customer.html.twig', [
                        'content' => $data['message'],
                        'deals' => $data['deals']
                    ]);
                    $msgSubject = $this->getParameter('customer.email.subject');
                } else {
//                    $content = $this->renderView('@Backend/Emails/salon.html.twig', [
                    $content = $this->renderView('@Backend/Emails/fight.html.twig', [
                        'content' => $data['message'],
                        'deals' => $data['deals']
                    ]);
//                    $msgSubject = "Here is a special offer from Plaza Mariachi";
                    $msgSubject = "Mayweather VS McGregor fight in Plaza Mariachi viewing party";
                }

               // echo $content; die ;

                $this->get('customer.manager')->sendEmail(
                    $users,
                    $customEmails,
                    $this->getParameter('customer.email.from'),
                    $msgSubject,
                    $content,
                    $this->getParameter('customer.email.to'),    // setting TO with parameter
                    true                                         // setting TO with the BCC
                );

                // Show the success message
                $this->get('session')->getFlashBag()->add('success', 'The mail was sent successfully.');

                return $this->redirect($this->generateUrl('mails_send'));
            }
        }

        return $this->render('mails/send.html.twig', array(
            'image_form' => $imageForm->createView(),
            'form' => $form->createView(),
            'post_images' => [],
        ));
    }
}
