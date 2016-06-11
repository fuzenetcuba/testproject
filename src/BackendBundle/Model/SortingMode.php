<?php

namespace BackendBundle\Model;

use BackendBundle\Entity\BaseEnum;

/**
 * Enum to use as the sorting mode for the deals page
 *
 * @package \BackendBundle\Model
 */
class SortingMode extends BaseEnum
{
    const
        SORT_NONE = 0,
        SORT_ENDING_SOON = 1,
        SORT_NEW_DEALS = 2,

        SORT_ALPHABETICALLY = 3,
        SORT_CATEGORY = 4,
        SORT_RECENT_DEALS = 5
    ;
}
