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

        $menu->addChild('Dashboard', array('route' => 'backend_homepage'))
            ->setAttribute('icon', 'fa fa-th-large');

        $menu->addChild('Pages', array('route' => 'post'))
            ->setAttribute('icon', 'fa fa-files-o');
        $menu->addChild('Media', array('route' => 'post_image'))
            ->setAttribute('icon', 'fa fa-picture-o');
        $menu->addChild('Settings', array('route' => 'settings'))
            ->setAttribute('icon', 'fa fa-cog');
        $menu->addChild('Brands', array('route' => 'business'))
            ->setAttribute('icon', 'fa fa-briefcase');
        $menu->addChild('Careers', array('route' => 'opening'))
            ->setAttribute('icon', 'fa fa-inbox');
        $menu->addChild('Audience', array('route' => 'customer'))
            ->setAttribute('icon', 'fa fa-user-circle');
        $menu->addChild('Marketing', array('route' => 'deal'))
            ->setAttribute('icon', 'fa fa-bar-chart');

        $menu['Pages']->addChild('Posts', ['route' => 'post']);
        $menu['Pages']->addChild('Press Room', ['route' => 'press']);

        $menu['Settings']->addChild('Appereance', ['route' => 'settings']);
        $menu['Settings']->addChild('Users & Roles', ['route' => 'user']);
        $menu['Settings']->addChild('Alerts', ['route' => 'alert']);
        $menu['Settings']->addChild('Sitemap', ['route' => 'backend_static_page', 'routeParameters' => ['name' => 'sitemap']]);

        $menu['Brands']->addChild('All Brands', ['route' => 'business']);
        $menu['Brands']->addChild('Business Categories', ['route' => 'category']);

        $menu['Careers']->addChild('Applicants', ['route' => 'candidate']);
        $menu['Careers']->addChild('Open Positions', ['route' => 'opening']);
        $menu['Careers']->addChild('Job Categories', ['route' => 'opening_category']);

        $menu['Audience']->addChild('Customers', ['route' => 'customer']);
        $menu['Audience']->addChild('Subscribers', ['route' => 'subscription']);

        $menu['Marketing']->addChild('Deals', ['route' => 'deal']);
        $menu['Marketing']->addChild('Rewards', ['route' => 'reward']);

        $menu['Marketing']->addChild('Mailer', ['route' => 'mails_send'])->setAttribute('data-admin', 'true');


        $menu->addChild('Logout', array('route' => 'fos_user_security_logout'))
            ->setAttribute('icon', 'fa fa-sign-out')
            ->setAttribute('class', 'special_link');

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