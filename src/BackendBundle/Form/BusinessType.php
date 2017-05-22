<?php

namespace BackendBundle\Form;

use BackendBundle\Form\DataTransformer\ArrayToDelimitedStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'label' => 'Facebook'))
            ->add('instagram', null, array(
                'label' => 'Instagram'))
            ->add('twitter', null, array(
                'label' => 'Twitter'))
            ->add('pinterest', null, array(
                'label' => 'Pinterest'))
            ->add('snapchat', null, array(
                'label' => 'Snapchat'))
            ->add('androidApp', null, array(
                'label' => 'Android App'))
            ->add('iosApp', null, array(
                'label' => 'iOS App'))
            ->add('categories')
            ->add('hours', TextType::class, array(
                'label' => "Hours of operation"
            ))
            ->add('website')
            ->add('email', TextType::class, ['required' => false])
            ->add('notifyEmails', TextType::class, ['required' => false])
            ->add('phone')
            ->add('isPublic', null, ['label' => 'Public'])
            ->add('mallMapDirections', TextareaType::class, array(
                'required' => false
            ))
            ->add('mallMapX', HiddenType::class)
            ->add('mallMapY', HiddenType::class)
            ->add('customers')
        ;

        $builder
            ->get('hours')->addModelTransformer(new ArrayToDelimitedStringTransformer(';', 0, 0));
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
