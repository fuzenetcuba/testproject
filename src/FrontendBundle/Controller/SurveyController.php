<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use BackendBundle\Entity\Feedback;


/**
 * Survey/Feedback controller
 */
class SurveyController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('FrontendBundle:Feedback:index.html.twig', [
            
        ]);
    }

    public function saveAction(Request $request)
    {
        $feedback = new Feedback();

        $feedback->satisfied = $request->request->get('satisfied');
        $feedback->recommended = $request->request->get('recommend');
        $feedback->whyNotRecommend = $request->request->get('recommendExplain');
        $feedback->storesSatisfied = $request->request->get('stores');
        $feedback->additionalStores = $request->request->get('storesExplain');
        $feedback->foodSatisfied = $request->request->get('food');
        $feedback->additionalFood = $request->request->get('foodExplain');
        $feedback->rate = $request->request->get('rate');
        $feedback->name = $request->request->get('name');
        $feedback->email = $request->request->get('email');
        $feedback->phone = $request->request->get('phone');

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($feedback);
        $em->flush();

        $content = $this->renderView('@Frontend/Feedback/mail.html.twig', [
            'feedback' => $feedback,
        ]);

        $message = \Swift_Message::newInstance()
            ->setSubject('New feedback')
            ->setFrom($this->getParameter('customer.email.from'))
            ->setTo('info@plazamariachi.com')
            ->setBody($content, 'text/html')
        ;

        $this->get('mailer')->send($message);

        return new JsonResponse(['ok' => true]);
    }
}