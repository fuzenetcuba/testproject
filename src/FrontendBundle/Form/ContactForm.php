<?php

namespace FrontendBundle\Form;

use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * {@inheritDoc}
 */
class ContactForm extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => new Length(['min' => 3, 'minMessage' => 'The full name must have 3 characters at least.'])
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('subject', TextType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => new Length(['min' => 3, 'minMessage' => 'The subject must have 3 characters at least.'])
            ])
            ->add('message', TextareaType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => new Length(['min' => 3, 'minMessage' => 'The message must have 3 characters at least.'])
            ])
            ->add('recaptcha', EWZRecaptchaType::class, array(
                'language' => 'en',
                'mapped' => false,
                'constraints' => new IsTrue()
            ));

    }

    /**
     * {@inheritDoc}
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'contact'
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'frontend_bundle_contact_form';
    }
}
