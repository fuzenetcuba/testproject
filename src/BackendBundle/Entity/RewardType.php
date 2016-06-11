<?php

namespace BackendBundle\Entity;

/**
 * Class to map and typecheck different types of Rewards
 *      EARNED_POINTS
 *      LOST_POINTS
 *
 * @package \BackendBundle\Entity
 */
final class RewardType extends BaseEnum
{
    const REWARD_EARNED_POINTS = 1,
          REWARD_LOST_POINTS = 2
    ;
}