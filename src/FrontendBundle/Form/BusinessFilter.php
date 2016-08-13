<?php

namespace FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * {@inheritDoc}
 */
class BusinessFilter extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', 'search', [
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'KEYWORDS']
            ])
            ->add('category', 'entity', [
                'required' => false,
                'class' => 'BackendBundle\Entity\Category',
                'attr' => ['class' => 'select2'],
                'empty_value' => 'ALL CATEGORIES',
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
            'translation_domain' => 'business'
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'frontend_bundle_business_filter';
    }
}
