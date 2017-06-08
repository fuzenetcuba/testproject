<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


/**
 * Class PressController
 *
 * @package \FrontendBundle\Controller
 */
class PressController extends Controller
{
    public function indexAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');

        $news = $this->get('doctrine.orm.entity_manager')
            ->getRepository('BackendBundle:PressPost')
            ->findAll()
        ;

        $pagination = $paginator->paginate(
            $news,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('FrontendBundle:Press:index.html.twig', [
            'news' => $pagination,
        ]);
    }
}