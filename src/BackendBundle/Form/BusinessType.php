<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BusinessType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('logoFile', FileType::class, array(
                'label' => 'Logo',
                'required' => false,
                'validation_groups' => array('creation')))
            ->add('socialMedia', null, array(
                'label' => 'Social Media'))
            ->add('androidApp', null, array(
                'label' => 'Android App'))
            ->add('iosApp', null, array(
                'label' => 'iOS App'))
            ->add('categories')
            ->add('hoursBegin', TimeType::class, array(
                'label' => "Hours of operation",
                'widget' => 'single_text',
                'html5' => false
            ))
            ->add('hoursEnd', TimeType::class, array('widget' => 'single_text', 'html5' => false))
            ->add('website')
            ->add('email')
            ->add('notifyEmails', 'text', ['required' => false])
            ->add('phone')
            ->add('isPublic', null, ['label' => 'Public'])
            ->add('mallMapDirections', TextareaType::class, array(
                'required' => false
            ))
            ->add('mallMapX', HiddenType::class)
            ->add('mallMapY', HiddenType::class)
            ->add('customers')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Business',
            'validation_groups' => array('Default', 'creation'),
            'translation_domain' => 'businessbackend'
        ));
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefixes default to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_business';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

}
