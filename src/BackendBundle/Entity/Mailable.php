<?php

namespace BackendBundle\Entity;

/**
 * Interface Mailable
 *
 * Contract to use on every mailable elements of the application,
 * customers, users, etc.
 *
 * @package BackendBundle\Entity
 */
interface Mailable
{
    public function getEmail();
}