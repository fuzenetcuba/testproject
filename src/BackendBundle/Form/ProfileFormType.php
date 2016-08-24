<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{

    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this->buildUserForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\SystemUser',
            'intention' => 'profile',
        ));
    }

    public function getBlockPrefix()
    {
        return 'backendbundle_user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle'
            ))
            ->add('firstName', null, array(
                'label' => 'First name',
                'translation_domain' => 'fosuser'
            ))
            ->add('lastName', null, array(
                'label' => 'Last name',
                'translation_domain' => 'fosuser'
            ))
            ->add('phone', null, array(
                'label' => 'Phone',
                'translation_domain' => 'fosuser'
            ))
            ->add('imageFile', FileType::class, array(
                'label' => 'Photo',
                'translation_domain' => 'fosuser',
                'required' => false));
    }

}
