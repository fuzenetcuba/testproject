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
class BusinessFixture extends OrderedYamlFixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $businesses = $this->loadData('business');

        $referenced = false;
        foreach ($businesses['Business'] as $business) {
            $entity = new Business();

            $entity->setName($business['name']);
            $entity->setDescription($business['description']);
            $entity->setEmail($business['email']);
            $entity->setPhone($business['phone']);
            $entity->setHoursBegin(date_create_from_format("H:i:s", $business['hours-begin']));
            $entity->setHoursEnd(date_create_from_format("H:i:s", $business['hours-end']));
            $entity->setSocialMedia($business['social-media']);
            $entity->setWebsite($business['website']);
            $entity->setMallMapDirections($business['mall-map-directions']);
            $entity->setLogo($business['logo']);

            $manager->persist($entity);

            if (false === $referenced) {
                $this->addReference('business', $entity);
                $referenced = true;
            } else {
                $this->setReference('other-business', $entity);
            }
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}