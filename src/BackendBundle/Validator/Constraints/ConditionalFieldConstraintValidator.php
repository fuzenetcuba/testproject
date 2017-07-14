<?php

namespace BackendBundle\Validator\Constraints;

use BackendBundle\Entity\PressPost;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ImageVideoConstraintValidator
 *
 * @package \BackendBundle\Validator\Constraints
 */
class ConditionalFieldConstraintValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var PressPost $entity */
        $entity = $this->context->getRoot()->getData();
        $image = $entity->getImage();
        $video = $entity->getVideo();

        if (null === $image) {
            $image = $entity->getImageFile();
        }

//        if (!($image === null ^ $video === null)) {
//            $this->context->buildViolation('ERROR ERROR ERROR')
//                ->addViolation()
//            ;
//        }

        if (null === $image && null === $video) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ first }}', $constraint->first)
                ->setParameter('{{ second }}', $constraint->second)
                ->addViolation()
            ;
        }

        if (null !== $image && null !== $video) {
            $this->context->buildViolation($constraint->one)
                ->setParameter('{{ first }}', $constraint->first)
                ->setParameter('{{ second }}', $constraint->second)
                ->addViolation()
            ;
        }
    }
}