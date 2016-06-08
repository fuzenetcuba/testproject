<?php

namespace BackendBundle\Form;

use Doctrine\Common\Cache\ArrayCache;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RewardType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array_combine(array_keys(\BackendBundle\Entity\RewardType::toArray()),array_keys(\BackendBundle\Entity\RewardType::toArray()));

        $builder
            ->add('name')
            ->add('description')
            ->add('type', ChoiceType::class, array(
                'choices' => $choices,
                'choices_as_values' => true,
            ))
            ->add('cost');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Reward'
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
        return 'backendbundle_reward';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

}