<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;                use Symfony\Component\Form\Extension\Core\Type\DateType;    
class AlertType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                ->add('message')
                        ->add('url')
                        ->add('checked')
                        ->add('date', DateType::class, array('widget' => 'single_text', 'html5' => false))
            ;
    }
        /**
    * @param OptionsResolver $resolver
    */
    public function configureOptions(OptionsResolver $resolver)
    {
    $resolver->setDefaults(array(
    'data_class' => 'BackendBundle\Entity\Alert'
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
    return 'backendbundle_alert';
    }


    /**
    * @return string
    */
    public function getName()
    {
    return $this->getBlockPrefix();
    }

}
