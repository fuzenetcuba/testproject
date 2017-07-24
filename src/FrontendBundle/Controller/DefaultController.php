<?php

namespace FrontendBundle\Controller;

use FrontendBundle\Form\ContactForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $topDeals = $this->get('deal.manager')->findTopDeals();

        $featuredBrands = $this->get('business.manager')->findAllFeatured();

        return $this->render('FrontendBundle:Default:index.html.twig',
            array(
                'name' => 'Dear Public User',
                'last_username' => $lastUsername,
                'error' => $error,
                'deals' => $topDeals,
                'featured_brands' => $featuredBrands
            )
        );
    }

    public function logedInAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $topDeals = $this->get('deal.manager')->findTopDeals();

        return $this->render('FrontendBundle:Default:index.html.twig',
            array(
                'name' => 'Loged In User',
                'last_username' => $lastUsername,
                'error' => $error,
                'deals' => $topDeals,
            )
        );
    }

    public function staticPageAction($name)
    {
        return $this->render("FrontendBundle:Static:" . $name . ".html.twig");
//        return $this->render("BackendBundle:Emails:customer.html.twig");
    }

    public function postPageAction($route)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('BackendBundle:Post')->findOneBy(array('route' => $route));

        if (!$post) {
            return $this->redirectToRoute('frontend_homepage');
        }

        return $this->render("FrontendBundle:Post:post.html.twig", array(
            'post' => $post
        ));
    }


    /**
     * Displays robots.txt.
     */
    public function robotsAction($template = null)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        return $this->render($template ?: sprintf(
            "FrontendBundle:Static:robots_%s.txt.twig",
            $this->container->getParameter('kernel.environment')
        ), array(), $response);
    }

    /**
     * Handles the logic of the mall map page
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     */
    public function mapAction(Request $request, $hl = null)
    {
        $businesses = $this->get('business.manager')->findAll();

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes([
            'categories',
            'deals',
            'customers',
            'openings',
        ]);

        if ($hl !== null) {
            $businessData = $this->get('business.manager')->findBySlug($hl);
        } else {
            $businessData = null;
        }

        $serializer = new Serializer([$normalizer], [$encoder]);

        return $this->render('@Frontend/map.html.twig', [
            'businesses' => $serializer->serialize($businesses, 'json'),
            'highlight' => $hl,
            'businessData' => $businessData
        ]);
    }


    /**
     * Displays Job Fair Page
     */
    public function jobfairAction(Request $request)
    {
        $openings = $this->get('opening.manager')->findMatchingOpenings()->getQuery()->execute();

        return $this->render("FrontendBundle:Static:jobfair.html.twig", [
            'openings' => $openings
        ]);
    }

    /**
     * Send a contact message
     *
     * @throws \Symfony\Component\Form\Exception\AlreadySubmittedException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     */
    public function contactMessageAction(Request $request)
    {
        $form = $this->createForm(ContactForm::class, null, array(
            'action' => $this->generateUrl('contact_message'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Send'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fullName = $form->get('fullName')->getData();
            $email = $form->get('email')->getData();
            $subject = $form->get('subject')->getData();
            $message = $form->get('message')->getData();
//            $secret = "6LeghiUUAAAAAMm0qccLzVGRGNsw0khHLIG8CiAQ";
//            $captcha = $request->request->get('g-recaptcha-response');

            $content = $this->renderView('@Backend/Emails/customer.html.twig', [
                'content' => sprintf('%s Has sent this message: <br /> <p>(%s)</p>',
                    $fullName, $message)
                ,
                'deals' => []
            ]);

            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->getParameter('customer.email.from'))
                ->setTo($this->getParameter('customer.email.to.contact'))
                ->setReplyTo($email)
                ->setBody($content, 'text/html');

            $messageSent = $this->get('swiftmailer.mailer')->send($message);

            if ($messageSent === 0) {
                $this->get('session')->getFlashBag()->add('danger', 'The message was not sent.');
            } else {
                $this->get('session')->getFlashBag()->add('success', 'The message was sent successfully.');
            }

            return $this->redirect($this->generateUrl('contact_message'));
        }

//        $this->get('session')->getFlashBag()->add('danger', 'The message was not sent.');
        return $this->render('FrontendBundle:Static:contact.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * Build the sitemap.xml
     */
    public function sitemapAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $urls = array();
        $hostname = $request->getSchemeAndHttpHost();

        // incluye urls multiidioma
        $languages = array('en', 'es');
        foreach ($languages as $lang) {

            // Include static routes
            $urls[] = array(
                'loc' => $this->get('router')->generate('frontend_homepage', array('_locale' => $lang)),
                'changefreq' => 'weekly',
                'priority' => '1.0'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('fos_user_registration_register', array('_locale' => $lang)),
                'changefreq' => 'yearly',
                'priority' => '0.2'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('fos_user_resetting_request', array('_locale' => $lang)),
                'changefreq' => 'yearly',
                'priority' => '0.2'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('frontend_static_page', array(
                    '_locale' => $lang,
                    'name' => 'about'
                )),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('deals', array('_locale' => $lang)),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('business_list', array('_locale' => $lang)),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('membership', array('_locale' => $lang)),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('subscription_request', array('_locale' => $lang)),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('mall_map', array('_locale' => $lang, 'slug' => 'all')),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('careers_index', array('_locale' => $lang)),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('app_robots', array('_locale' => $lang)),
                'changefreq' => 'yearly',
                'priority' => '0.2'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('job_fair', array('_locale' => $lang)),
                'changefreq' => 'monthly',
                'priority' => '0.5'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('survey', array('_locale' => $lang)),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('calendar', array(
                    '_locale' => $lang,
                    'view' => 'music',
                    'key' => 'timeline'
                )),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            );

            $urls[] = array(
                'loc' => $this->get('router')->generate('press_page', array('_locale' => $lang)),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            );

            // Include url of Deals from DB
            $deals = $em->getRepository('BackendBundle:Deal')->findAll();
            foreach ($deals as $deal) {
                $urls[] = array(
                    'loc' => $this->get('router')->generate('deal_details', array(
                        '_locale' => $lang,
                        'slug' => $deal->getSlug()
                    )),
                    'changefreq' => 'monthly',
                    'priority' => '0.3'
                );
            }

            // Include url of Business from DB
            $businesses = $em->getRepository('BackendBundle:Business')->findAll();
            foreach ($businesses as $business) {
                $urls[] = array(
                    'loc' => $this->get('router')->generate('business_details', array(
                        '_locale' => $lang,
                        'slug' => $business->getSlug()
                    )),
                    'changefreq' => 'weekly',
                    'priority' => '0.5'
                );
            }

            // Include url of Careers from DB
            $openings = $em->getRepository('BackendBundle:Opening')->findAll();
            foreach ($openings as $opening) {
                $urls[] = array(
                    'loc' => $this->get('router')->generate('careers_apply', array(
                        '_locale' => $lang,
                        'slug' => $opening->getSlug()
                    )),
                    'changefreq' => 'weekly',
                    'priority' => '0.5'
                );
            }

            // Include url of Posts from DB
            $posts = $em->getRepository('BackendBundle:Post')->findAll();
            foreach ($posts as $post) {
                $urls[] = array(
                    'loc' => $this->get('router')->generate('frontend_post_page', array(
                        '_locale' => $lang,
                        'route' => $post->getRoute()
                    )),
                    'changefreq' => 'weekly',
                    'priority' => '0.5'
                );
            }

            // ...
        }


        $response = $this->render('FrontendBundle:Static:sitemap.xml.twig', array(
            'urls' => $urls,
            'hostname' => $hostname
        ));

        $response->headers->add(array('Content-Type' => 'application/xml'));
        return $response;
    }
}
