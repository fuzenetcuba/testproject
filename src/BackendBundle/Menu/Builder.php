<?php

namespace BackendBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Dashboard', array('route' => 'backend_homepage'));

        $menu->addChild('Pages', array('route' => 'backend_post'));
        $menu->addChild('Media', array('route' => 'backend_post_image'));
        $menu->addChild('Settings', array('route' => 'backend_settings'));
        $menu->addChild('Brands', array('route' => 'backend_business'));
        $menu->addChild('Careers', array('route' => 'backend_opening'));
        $menu->addChild('Audience', array('route' => 'backend_customer'));
        $menu->addChild('Marketing', array('route' => 'backend_deals'));

        $menu['Pages']->addChild('Posts', ['route' => 'backend_post']);
        $menu['Pages']->addChild('Press Room', ['route' => 'backend_press']);

        $menu['Settings']->addChild('Appereance', ['route' => 'backend_press']);
        $menu['Settings']->addChild('Users & Roles', ['route' => 'config_routing_user']);
        $menu['Settings']->addChild('Alerts', ['route' => 'backend_alerts']);

        $menu['Brands']->addChild('All Brands', ['route' => 'backend_business']);
        $menu['Brands']->addChild('Business Categories', ['route' => 'backend_category']);

        $menu['Careers']->addChild('Applicants', ['route' => 'backend_opening']);
        $menu['Careers']->addChild('Open Positions', ['route' => 'backend_candidate']);
        $menu['Careers']->addChild('Job Categories', ['route' => 'backend_opening_category']);

        $menu['Audience']->addChild('Customers', ['route' => 'backend_customer']);
        $menu['Audience']->addChild('Subscribers', ['route' => 'backend_subscription']);

        $menu['Marketing']->addChild('Deals', ['route' => 'backend_deal']);
        $menu['Marketing']->addChild('Rewards', ['route' => 'backend_reward']);
        $menu['Marketing']->addChild('Mailer', ['route' => 'backend_mails']);

//        // access services from the container!
//        $em = $this->container->get('doctrine')->getManager();
//        // findMostRecent and Blog are just imaginary examples
//        $blog = $em->getRepository('AppBundle:Blog')->findMostRecent();
//
//        $menu->addChild('Latest Blog Post', array(
//            'route' => 'blog_show',
//            'routeParameters' => array('id' => $blog->getId())
//        ));

        return $menu;
    }
}