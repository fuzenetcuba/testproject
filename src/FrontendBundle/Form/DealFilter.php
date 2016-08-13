<?php

namespace FrontendBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * {@inheritDoc}
 */
class DealFilter extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('business', EntityType::class, [
                'required' => false,
                'class' => 'BackendBundle\Entity\Business',
                'attr' => ['class' => 'select2'],
                'empty_value' => 'ALL BUSINESS',
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
        $resolver->setDefaults([
            'translation_domain' => 'deals'
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'deal_filter';
    }
}
