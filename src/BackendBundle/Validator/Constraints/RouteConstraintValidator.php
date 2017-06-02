<?php

namespace BackendBundle\Validator\Constraints;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class RouteConstraintValidator
 *
 * @package \BackendBundle\Validator\Constraints
 */
class RouteConstraintValidator extends ConstraintValidator
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        $matcher = $this->router->getMatcher();

        try {
            $route = $matcher->match($value);

            if ($route && false === strpos($route['_route'], 'frontend_post_page')) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ route }}', $value)
                    ->addViolation()
                ;
            }
        } catch (ResourceNotFoundException $exception) {
        }
    }
}