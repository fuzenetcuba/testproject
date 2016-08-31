<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;

class userType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choiceList = new SimpleChoiceList(array(
            'Customer' => 'ROLE_CUSTOMER',
            'Business' => 'ROLE_BUSINESS',
            'Admin' => 'ROLE_ADMIN'
        ));
        $builder
            ->add('username', TextType::class, array('label' => 'Username', 'attr' => array('autocomplete' => 'off')))
            ->add('roles', ChoiceType::class, array(
                'label' => 'Roles', 'attr' => array('autocomplete' => 'off'),
                'choices' => array(
                    'ROLE_CUSTOMER' => 'Customer',
                    'ROLE_BUSINESS' => 'Business',
                    'ROLE_ADMIN' => 'Admin'
                ),
                'expanded' => true,
                'multiple' => true,
                'placeholder' => 'Choose the role',
            ))
            ->add('password', RepeatedType::class, array(
                'invalid_message' => 'Passwords have to be equal.',
                'first_name' => 'Password',
                'second_name' => 'Repeat',
                'type' => 'password',
                'required' => false,
            ))
            ->add('email', EmailType::class, array('required' => true, 'attr' => array('autocomplete' => 'off')))
            ->add('enabled')
            ->add('business')
            ->add('rewards')
            ->add('firstName')
            ->add('lastName')
            ->add('phone')
            ->add('imageFile', FileType::class, array(
                'label' => 'Photo',
                'required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\SystemUser',
            'translation_domain' => 'userbackend'
        ));
    }

    public function getName()
    {
        return 'backendbundle_usertype';
    }

}
