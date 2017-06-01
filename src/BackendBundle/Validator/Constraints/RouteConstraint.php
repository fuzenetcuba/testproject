<?php

namespace BackendBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class RouteConstraint
 *
 * @Annotation
 *
 * @package \BackendBundle\Validator\Constraints
 */
class RouteConstraint extends Constraint
{
    public $message = 'The route "{{ route }}" is already in use by this application';
}