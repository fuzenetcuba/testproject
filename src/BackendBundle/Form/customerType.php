<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class customerType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array('label' => 'Username', 'attr' => array('autocomplete' => 'off')))
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\SystemUser'
        ));
    }

    public function getName()
    {
        return 'backendbundle_customertype';
    }

}
