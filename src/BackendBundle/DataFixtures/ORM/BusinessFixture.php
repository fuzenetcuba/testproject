<?php

namespace BackendBundle\DataFixtures\ORM;

use BackendBundle\Entity\Business;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Loads sample data into the business table, for testing or bootstrapping
 * the application
 *
 * @package \BackendBundle\DataFixtures\ORM
 */
class BusinessFixture extends YamlFixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $businesses = $this->loadData('business');

        foreach ($businesses['Business'] as $business) {
            $entity = new Business();

            $entity->setName($business['name']);
            $entity->setDescription($business['description']);
            $entity->setPhone($business['phone']);
            $entity->setSocialMedia($business['social-media']);
            $entity->setLogo($business['logo']);

            $manager->persist($entity);

            $this->addReference('business', $entity);
        }

        $manager->flush();
    }
}