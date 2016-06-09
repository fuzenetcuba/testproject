<?php

namespace BackendBundle\DataFixtures\ORM;
use BackendBundle\Entity\Deal;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Loads sample data into the deal table, for testing or bootstrapping
 * the application
 *
 * @package \BackendBundle\DataFixtures\ORM
 */
class DealFixture extends OrderedYamlFixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $deals = $this->loadData('deals');

        foreach ($deals['Deals'] as $deal) {
            $object = new Deal();

            $object->setName($deal['name']);
            $object->setDescription($deal['description']);
            $object->setStartsAt(
                new \DateTime($deal['start'] ? $deal['start'] : 'NOW')
            );
            $object->setEndsAt(
                new \DateTime($deal['ends'] ? $deal['ends'] : '+10 DAYS')
            );
            $object->setImage($deal['image']);
            $object->setPoints($deal['points']);

            /** @var Business $business */
            $business = $this->getReference('business');
            $object->setBusiness($business);

            $manager->persist($object);
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
        return 3;
    }
}