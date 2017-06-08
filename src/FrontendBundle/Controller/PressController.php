<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


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

//        $qb = $this->get('doctrine.orm.entity_manager')->createQueryBuilder();
//        $qb->select('BackendBundle:PressPost', 'p')
//            ->orderBy('p.createdOn', 'desc');
        $news = $this->get('doctrine.orm.entity_manager')
            ->getRepository('BackendBundle:PressPost')
            ->findBy([], ['createdOn' => 'DESC'])
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