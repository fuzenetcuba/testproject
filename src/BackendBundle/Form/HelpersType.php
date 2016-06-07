<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class HelpersType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', DateType::class, array('widget' => 'single_text', 'html5' => false))
            ->add('time', TimeType::class, array('widget' => 'single_text', 'html5' => false))
            ->add('dateTime', DateType::class, array('widget' => 'single_text', 'html5' => false))
            ->add('dateRange')
            ->add('timeRange')
            ->add('isActive')
            ->add('imageFile', FileType::class, array('required' => false, 'validation_groups' => array('creation')))

            ->add('updatedAt', DateType::class, array('widget' => 'single_text', 'html5' => false))
            ->add('person');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Helpers',
            'validation_groups' => array('Default', 'creation')
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
        return 'backendbundle_helpers';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

}
