<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SettingsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('websiteBrand')
            ->add('websiteName')
            ->add('websiteLastname')
            ->add('websiteSlogan')
            ->add('websiteCopyright')
            ->add('websiteAddress')
            ->add('websitePhone')
            ->add('websiteFax')
            ->add('websiteEmail')
            ->add('websiteUrlTwitter')
            ->add('websiteUrlGoogle')
            ->add('websiteUrlFacebook')
            ->add('websiteUrlFlickr')
            ->add('websiteUrlInstagram')
            ->add('pinterest')
            ->add('snapchat')
            ->add('websiteLogoImage', FileType::class, array(
                'required' => false,
                'validation_groups' => array('creation')))
            ->add('websiteBannerImage', FileType::class, array(
                'required' => false,
                'validation_groups' => array('creation')))
            ->add('mobileBannerImage', FileType::class, array(
                'required' => false,
                'validation_groups' => array('creation')))
            ->add('tabletBannerImage', FileType::class, array(
                'required' => false,
                'validation_groups' => array('creation')))
            ->add('headerFeatureBrands')
            ->add('subheaderFeatureBrands')
            ->add('headerBecomeMemberToday')
            ->add('subheaderBecomeMemberToday')
            ->add('headerTodaysHoursOperation')
            ->add('subheaderTodaysHoursOperation')
            ->add('headerTopDealsOfDay')
            ->add('subheaderTopDealsOfDay')
            ->add('headerContactUs')
            ->add('subheaderContactUs')
            ->add('headerBusinessActiveDeals')
            ->add('subheaderBusinessActiveDeals')
            ->add('headerBusinessRelatedBusinesses')
            ->add('subheaderBusinessRelatedBusinesses')
            ->add('headerStayClose')
            ->add('subheaderStayClose')
            ->add('headerLogin')
            ->add('textLogin')
            ->add('headerAbout')
            ->add('subheaderAbout')
            ->add('textAbout')
            ->add('headerSubscribeForDeals')
            ->add('subheaderSubscribeForDeals')
            ->add('textSubscribeForDeals');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Settings',
            'validation_groups' => array('Default', 'creation'),
            'translation_domain' => 'settingsbackend'
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
        return 'backendbundle_settings';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

}
