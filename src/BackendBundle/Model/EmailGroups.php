<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\BaseEnum;

/**
 * Enum class to use when working with the configured email groups in the
 * system
 *
 * @package \BackendBundle\Model
 */
class EmailGroups extends BaseEnum
{
    const
        SUBSCRIBED_USERS = 1,
        REGISTERED_USERS = 2,
        ALL_USERS = 3
    ;
}