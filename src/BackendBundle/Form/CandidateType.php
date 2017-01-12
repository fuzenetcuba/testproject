<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;                                                    use Symfony\Component\Form\Extension\Core\Type\DateType;                                                                                                                        
class CandidateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                ->add('lastName')
                        ->add('firstName')
                        ->add('middleName')
                        ->add('address')
                        ->add('city')
                        ->add('state')
                        ->add('zipCode')
                        ->add('socialNumber')
                        ->add('adult')
                        ->add('phone')
                        ->add('availableHours')
                        ->add('weekAvailable')
                        ->add('startDate', DateType::class, array('widget' => 'single_text', 'html5' => false))
                        ->add('salary')
                        ->add('availability')
                        ->add('hasLicense')
                        ->add('licenseNumber')
                        ->add('licenseState')
                        ->add('licenseExpiration')
                        ->add('legalWorker')
                        ->add('crime')
                        ->add('crimeExplain')
                        ->add('background')
                        ->add('backExplain')
                        ->add('yearsHighschool')
                        ->add('hasDiploma')
                        ->add('hasGED')
                        ->add('schools')
                        ->add('yearsCollege')
                        ->add('collegeSchool')
                        ->add('collegeState')
                        ->add('major')
                        ->add('degree')
                        ->add('gpa')
                        ->add('contactEmployers')
                        ->add('employmentName')
                        ->add('employers')
                        ->add('references')
                        ->add('skills')
                        ->add('bestFit')
                        ->add('cv')
                        ->add('coverLetter')
                ->add('opening')    ;
    }
        /**
    * @param OptionsResolver $resolver
    */
    public function configureOptions(OptionsResolver $resolver)
    {
    $resolver->setDefaults(array(
    'data_class' => 'BackendBundle\Entity\Candidate'
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
    return 'backendbundle_candidate';
    }


    /**
    * @return string
    */
    public function getName()
    {
    return $this->getBlockPrefix();
    }

}
