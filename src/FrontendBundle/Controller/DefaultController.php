<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $featuredBrands = [];
        $featuredBrands[] = $this->get('business.manager')->findBySlug('xenote-restaurant');
        $featuredBrands[] = $this->get('business.manager')->findBySlug('tito-s-playland');
        $featuredBrands[] = $this->get('business.manager')->findBySlug('maz-fresco');

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
     */
    public function sendContactMessageAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new Response(json_encode(array('errorMessage' => 'You can access this only using Ajax!')), 400);
        }

        $secret = "6LeghiUUAAAAAMm0qccLzVGRGNsw0khHLIG8CiAQ";
        $fullName = $request->request->get('full-name');
        $email = $request->request->get('email');
        $subject = $request->request->get('subject');
        $message = $request->request->get('message');
        $captcha = $request->request->get('g-recaptcha-response');

        if ($fullName == null || $fullName == ""
            || $email == null || $email == ""
            || $subject == null || $subject == ""
            || $message == null || $message == ""
        ) {
            return new Response(json_encode(array('errorMessage' => 'All fields are required!')), 200);
        } elseif (!$captcha || $captcha == null || $captcha == "") {
            return new Response(json_encode(array('errorMessage' => 'You must check the captcha field!')), 200);
        } else {
            $url = "https://www.google.com/recaptcha/api/siteverify"
                . "?secret=" . $secret
                . "&response=" . $captcha
                . "&remoteip=" . $_SERVER['REMOTE_ADDR'];

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_POST, 3);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = json_decode(curl_exec($ch));
            curl_close($ch);

            /**
             * JSON response of the request to Google
             *  {
             *      "success": true | false,
             *      "challenge_ts": timestamp,  // timestamp of the challenge load (ISO format yyyy-MM-dd'T'HH:mm:ssZZ)
             *      "hostname": string,         // the hostname of the site where the reCAPTCHA was solved
             *      "error-codes": [...]        // optional
             *  }
             */

            if ($response->success == true) {
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
                    ->setBody($content, 'text/html')
                    ->attach(null);

                $this->get('mailer')->send($message);

                return new Response(json_encode(array('message' => 'The message was sent successfully')), 200);
            } else {
                return new Response(json_encode(array('errorMessage' => 'You are identify as a robot')), 200);
            }
        }
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
