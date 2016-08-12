<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Validator\Constraints\IsTrue;

class registrationType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        parent::buildForm($builder, $options);

        $builder
            ->add('username', TextType::class, array('label' => 'Username', 'attr' => array('autocomplete' => 'off')))
            ->add('plainPassword', RepeatedType::class, array(
                'invalid_message' => 'Passwords have to be equal.',
                'first_name' => 'Password',
                'second_name' => 'Repeat',
                'type' => 'password',
                'required' => true,
            ))
            ->add('email', EmailType::class, array('required' => true, 'attr' => array('autocomplete' => 'off')))
            ->add('firstName')
            ->add('lastName')
//            ->add('phone')
//            ->add('imageFile', FileType::class, array(
//                'label' => 'Photo',
//                'required' => false))
//            ->add('userId', new userType(), array(
//                'constraints' => new Valid()
//            ))
            ->add('acceptTerms', CheckboxType::class, array(
                'mapped' => false,
                'constraints' => new IsTrue()
            ))
        ;
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'backendbundle_user_registration';
    }

}
