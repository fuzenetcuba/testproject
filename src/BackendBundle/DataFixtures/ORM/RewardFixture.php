<?php

namespace BackendBundle\DataFixtures\ORM;
use BackendBundle\Entity\Reward;
use BackendBundle\Entity\RewardEvents;
use BackendBundle\Entity\RewardType;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class RewardFixture
 *
 * @package \BackendBundle\DataFixtures\ORM
 */
class RewardFixture extends OrderedYamlFixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     *
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function load(ObjectManager $manager)
    {
        $rewards = $this->loadData('reward');

        foreach ($rewards['Rewards'] as $reward) {
            /** @var Reward $object */
            $object = new Reward();

            $object->setName($reward['name']);
            $object->setDescription($reward['description']);
            $object->setType(RewardType::toArray()[$reward['type']]);
            $object->setCost($reward['cost']);
            $object->setEvent(RewardEvents::toArray()[$reward['event']]);

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
        return 4;
    }
}