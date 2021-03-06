<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;                                            
class FeedbackType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                ->add('satisfied')
                        ->add('recommended')
                        ->add('whyNotRecommend')
                        ->add('storesSatisfied')
                        ->add('additionalStores')
                        ->add('foodSatisfied')
                        ->add('additionalFood')
                        ->add('rate')
                        ->add('name')
                        ->add('email')
                        ->add('phone')
            ;
    }
        /**
    * @param OptionsResolver $resolver
    */
    public function configureOptions(OptionsResolver $resolver)
    {
    $resolver->setDefaults(array(
    'data_class' => 'BackendBundle\Entity\Feedback'
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
    return 'backendbundle_feedback';
    }


    /**
    * @return string
    */
    public function getName()
    {
    return $this->getBlockPrefix();
    }

}
