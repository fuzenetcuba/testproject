<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $bMgr = $this->get('business.manager');
        $cantBusiness = count($bMgr->findAll());

        $cMgr = $this->get('category.manager');
        $cantCategory = count($cMgr->findAll());

        $dMgr = $this->get('deal.manager');
        $cantDeal = count($dMgr->findAll());

        $cuMgr = $this->get('customer.manager');
        $cantCustomer = count($cuMgr->findAll());

        // find top businesses by number of deals
        $queryTopBNumDeals = $em->getRepository('BackendBundle:Business')->findTopBusinessByCantDeals();
        if (count($queryTopBNumDeals) > 5) {
            $topBNumDeals = array_slice($queryTopBNumDeals, 0, 5);
        } else {
            $topBNumDeals = $queryTopBNumDeals;
        }

        // find top deals by created date
        $topDealsByCreatedDate = $em->getRepository('BackendBundle:Deal')->findTopDeals(5);

        return $this->render('BackendBundle:Default:index.html.twig', array(
            'cant_business' => $cantBusiness,
            'cant_category' => $cantCategory,
            'cant_deal' => $cantDeal,
            'cant_customer' => $cantCustomer,
            'top_business_num_deals' => $topBNumDeals,
            'top_deals_created_date' => $topDealsByCreatedDate
        ));
    }

    public function getCsrfTokenAction(){
        $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        return new Response($csrfToken);
    }
}
