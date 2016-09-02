<?php

namespace FrontendBundle\Listener;

use BackendBundle\Entity\Reward;
use BackendBundle\Entity\RewardEvents;
use BackendBundle\Entity\RewardType;
use BackendBundle\Entity\SystemUser;
use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Model\UserManagerInterface;
use FrontendBundle\Event\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserRegisteredRewardListener
 *
 * @package \FrontendBundle\Listener
 */
class UserRegisteredRewardListener implements EventSubscriberInterface
{
    /**
     * @var \FOS\UserBundle\Model\UserManagerInterface
     */
    private $manager;
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    public function __construct(UserManagerInterface $manager, EntityRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            RewardEvents::REWARD_ON_REGISTER => 'onUserRegistered',
        ];
    }

    public function onUserRegistered(Event $event)
    {
        /** @var SystemUser $user */
        $user = $event->getSubject();

        /** @var Reward $reward */
        $reward = $this->repository->findOneBy([
            'event' => $event->getName(),
        ]);

        if (!$reward || !$user) {
            // We did not found an active reward for this event, do nothing
            // TODO Perhaps we should log this somewhere?
            return ;
        }

        // increase the user reward points
        if (RewardType::REWARD_EARNED_POINTS === $reward->getType()) {
            $user->incrementPoints($reward->getCost());
        } else if (RewardType::REWARD_LOST_POINTS === $reward->getType()) {
            $user->reducePoints($reward->getCost());
        }

        $this->manager->updateUser($user);
    }
}