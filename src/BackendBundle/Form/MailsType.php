<?php

namespace BackendBundle\Form;

use BackendBundle\Form\DataTransformer\ArrayToDelimitedStringTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class MailsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('registeredUsers', EntityType::class, array(
                'label' => 'Registered users',
                'required' => false,
                'class' => 'BackendBundle\Entity\SystemUser',
                'choice_label' => 'email',
                'multiple' => true,
                'choices_as_values' => true
            ))
            ->add('subscribedUsers', EntityType::class, array(
                'required' => false,
                'class' => 'BackendBundle\Entity\Subscription',
                'choice_label' => 'email',
                'multiple' => true,
                'choices_as_values' => true
            ))
            ->add('customAddresses', TextType::class, array(
                'required' => false,
                'pattern' => '^.+@\w+\.\w+(;.+@\w+\.\w+)*$'
            ))
            ->add('groupOfUsers', ChoiceType::class, array(
                'required' => false,
                'choices' => array(
                    'Subscribed users addresses' => 1,
                    'Registered users addresses' => 2,
                    'All addresses collected' => 3
                ),
                'empty_value' => '-- SELECT GROUP OF USERS --',
                'choices_as_values' => true
            ))
            ->add('categories', EntityType::class, array(
                'label' => 'Users interested in',
                'required' => false,
                'class' => 'BackendBundle\Entity\Category',
                'multiple' => true,
                'choices_as_values' => true
            ))
            ->add('message', TextareaType::class, array(
                'required' => true
            ))
            ->add('deals', EntityType::class, array(
                'label' => 'Attach deals',
                'required' => false,
                'class' => 'BackendBundle\Entity\Deal',
                'multiple' => true,
                'choices_as_values' => true
            ))
            ->get('customAddresses')->addModelTransformer(new ArrayToDelimitedStringTransformer(';', 0, 0));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
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
        return 'frontend_bundle_mails_send';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

}
