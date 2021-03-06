<?php

namespace BackendBundle\Entity;

/**
 * Class to map and typecheck different type of events
 * related to Rewards
 *
 * @package \BackendBundle\Entity
 */
final class RewardEvents extends BaseEnum
{
    const
        REWARD_ON_REGISTER = 'user.created'
    ;
}