<?php

namespace BackendBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form class for the Opening entity
 */
class OpeningType extends AbstractType
{
    private $select = false;

    public function __construct($select = false)
    {
        $this->select = $select;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->select) {
            $builder
                ->add('position', EntityType::class, [
                    'required' => false,
                    'attr' => ['class' => 'form-control select2-field'],
                    'class' => 'BackendBundle\Entity\Opening',
                    'choice_label' => 'position',
                    'empty_value' => 'All the openings',
                ]);
        } else {
            $builder
                ->add('position', TextType::class, [
                    'required' => true,
                    'attr' => ['class' => 'form-control select2-field'],
                ]);
        }
        if ($this->select) {
            $builder
                ->add('categories', EntityType::class, [
                    'required' => false,
                    'attr' => ['class' => 'form-control select2-field'],
                    'class' => 'BackendBundle\Entity\OpeningCategory',
                    'choice_label' => 'name',
                    'empty_value' => 'All opening categories',
                ]);
        } else {
            $builder
                ->add('categories');
        }
        $builder
            // ->add('department')
            ->add('description', 'textarea', [
                'attr' => ['rows' => '10']
            ]);

        if ($this->select) {
            $builder
                ->add('business', EntityType::class, [
                    'class' => 'BackendBundle\Entity\Business',
                    // 'query_builder' => function (EntityRepository $er) {
                    //     return $er->createQueryBuilder('b')
                    //         ->join('b.openings', 'o')
                    //         ->orderBy('b.name', 'ASC');
                    // },
                    'choice_label' => 'name',
                    'empty_value' => 'All the businesses',
                    'required' => false,
                    'attr' => ['class' => 'form-control select2-field', 'v-model' => 'currentOpeningCategory']
                ]);
        } else {
            $builder
                ->add('business', EntityType::class, [
                    'class' => 'BackendBundle\Entity\Business',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('b')
                            // ->join('b.openings', 'o')
                            ->orderBy('b.name', 'ASC');
                    },
                    'choice_label' => 'name',
                    'required' => false,
                    'attr' => ['class' => 'form-control select2-field']
                ]);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Opening',
            'translation_domain' => 'careers'
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'opening';
    }
}
