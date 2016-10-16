<?php

namespace BackendBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form class for the Opening entity
 */
class OpeningType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control select2-field'],
                'class' => 'BackendBundle\Entity\Opening',
                'choice_label' => 'position',
                'empty_value' => '',
            ])
            ->add('description')
            ->add('business', null, [
                'required' => false,
                'attr' => ['class' => 'form-control select2-field']
            ])
        ;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Opening',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'backend_bundle_opening_type';
    }
}