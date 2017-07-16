<?php

namespace BackendBundle\Form;

use BackendBundle\Form\DataTransformer\ArrayToDelimitedStringTransformer;
use BackendBundle\Model\EmailGroups;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
     * @param array                $options
     *
     * @throws \Symfony\Component\Form\Exception\InvalidArgumentException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('registeredUsers', EntityType::class, array(
                'label' => 'Registered users',
                'required' => false,
                'class' => 'BackendBundle\Entity\SystemUser',
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('q')
                        ->select('f')
                        ->from('BackendBundle:SystemUser', 'f')
                        ->where('f.roles LIKE :find')
                        ->andWhere('f.subscribed = true')
                        ->setParameter('find', '%ROLE_CUSTOMER%');
                },
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
                    'Subscribed users addresses' => EmailGroups::SUBSCRIBED_USERS,
                    'Registered users addresses' => EmailGroups::REGISTERED_USERS,
                    'All addresses collected' => EmailGroups::ALL_USERS,
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
            ->add('template', CheckboxType::class, ['required' => false, 'attr' => [
                'class' => '.i-checks'
            ]])
            ->get('customAddresses')->addModelTransformer(new ArrayToDelimitedStringTransformer(';', 0, 0));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'mailsbackend'
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
