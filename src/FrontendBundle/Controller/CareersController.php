<?php

namespace FrontendBundle\Controller;

use BackendBundle\Model\SortingMode;
use FrontendBundle\Form\BusinessFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * {@inheritDoc}
 */
class CareersController extends Controller
{
    public function indexAction(){
        return $this->render('@Frontend/Careers/index.html.twig', array(
            
        ));
    }

    public function findAction(){
        return $this->render('@Frontend/Careers/find.html.twig', array(

        ));
    }

    public function applyAction(){
        return $this->render('@Frontend/Careers/apply.html.twig', array(

        ));
    }
}
