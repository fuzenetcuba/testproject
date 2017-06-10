<?php

namespace BackendBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class ImageVideoConstraint
 *
 * @Annotation
 * @package \BackendBundle\Validator\Constraints
 */
class ConditionalFieldConstraint extends Constraint
{
    public $first;
    public $second;
    public $message = 'You need to specify either an {{ first }} or {{ second }}';
    public $one = 'You can not provide both fields: {{ first }} and {{ second }}';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}