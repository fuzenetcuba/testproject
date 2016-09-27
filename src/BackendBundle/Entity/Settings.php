<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Settings class
 *
 * @package \BackendBundle\Entity
 *
 * @Vich\Uploadable
 * @ORM\Entity()
 * @ORM\Table(name="settings")
 */
class Settings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="The brand must have 3 characters or more")
     */
    private $websiteBrand;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="The name must have 3 characters or more")
     */
    private $websiteName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="The lastname must have 3 characters or more")
     */
    private $websiteLastname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="The slogan must have 3 characters or more")
     */
    private $websiteSlogan;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="The copyright must have 3 characters or more")
     */
    private $websiteCopyright;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="The address must have 3 characters or more")
     */
    private $websiteAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="6", minMessage="The phone must have 6 characters or more")
     */
    private $websitePhone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="6", minMessage="The fax must have 6 characters or more")
     */
    private $websiteFax;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\Email()
     * @Assert\NotBlank()
     * @Assert\Length(min="5", minMessage="The email must have 5 characters or more")
     */
    private $websiteEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Url()
     */
    private $websiteUrlTwitter;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Url()
     */
    private $websiteUrlGoogle;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Url()
     */
    private $websiteUrlFacebook;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Url()
     */
    private $websiteUrlFlickr;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Url()
     */
    private $websiteUrlInstagram;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $websiteLogo;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @var File
     *
     * @Vich\UploadableField(mapping="setting_image", fileNameProperty="websiteLogo",
     *      groups={"creation"}
     * )
     * @Assert\Image(
     *     minWidth="16",
     *     maxWidth="3000",
     *     minWidthMessage="The image must have a with between 16 and 3000 pixels",
     *     maxWidthMessage="The image must have a with between 16 and 3000 pixels",
     *     minHeight="16",
     *     maxHeight="3000",
     *     minHeightMessage="The image must have a height between 16 and 3000 pixels",
     *     maxHeightMessage="The image must have a height between 16 and 3000 pixels",
     *     maxSize="5M",
     *     maxSizeMessage="The image must have 5 MB (megabytes) or less"
     * )
     */
    private $websiteLogoImage;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $websiteBanner;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @var File
     *
     * @Vich\UploadableField(mapping="setting_image", fileNameProperty="websiteBanner",
     *      groups={"creation"}
     * )
     * @Assert\Image(
     *     minWidth="16",
     *     maxWidth="3000",
     *     minWidthMessage="The image must have a with between 16 and 3000 pixels",
     *     maxWidthMessage="The image must have a with between 16 and 3000 pixels",
     *     minHeight="16",
     *     maxHeight="3000",
     *     minHeightMessage="The image must have a height between 16 and 3000 pixels",
     *     maxHeightMessage="The image must have a height between 16 and 3000 pixels",
     *     maxSize="5M",
     *     maxSizeMessage="The image must have 5 MB (megabytes) or less"
     * )
     */
    private $websiteBannerImage;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $headerFeatureBrands;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $subheaderFeatureBrands;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $headerBecomeMemberToday;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $subheaderBecomeMemberToday;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $headerTodaysHoursOperation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $subheaderTodaysHoursOperation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $headerTopDealsOfDay;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $subheaderTopDealsOfDay;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $headerContactUs;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $subheaderContactUs;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $headerBusinessActiveDeals;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $subheaderBusinessActiveDeals;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $headerBusinessRelatedBusinesses;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $subheaderBusinessRelatedBusinesses;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $headerStayClose;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $subheaderStayClose;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $headerLogin;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="text")
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $textLogin;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $headerAbout;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $subheaderAbout;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="text")
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $textAbout;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $headerSubscribeForDeals;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $subheaderSubscribeForDeals;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="text")
     * @Assert\Length(min="3", minMessage="This field must have 3 characters or more")
     */
    private $textSubscribeForDeals;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();

        if (null === $this->updatedAt) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function __toString()
    {
        return "" . $this->id;
    }


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getWebsiteLogo()
    {
        return $this->websiteLogo;
    }

    /**
     * @param string $websiteLogo
     */
    public function setWebsiteLogo($websiteLogo)
    {
        $this->websiteLogo = $websiteLogo;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\File $websiteLogoImage
     */
    public function setWebsiteLogoImage(File $websiteLogoImage = null)
    {
        $this->websiteLogoImage = $websiteLogoImage;

        if ($websiteLogoImage) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getWebsiteLogoImage()
    {
        return $this->websiteLogoImage;
    }

    /**
     * @return string
     */
    public function getWebsiteBanner()
    {
        return $this->websiteBanner;
    }

    /**
     * @param string $websiteBanner
     */
    public function setWebsiteBanner($websiteBanner)
    {
        $this->websiteBanner = $websiteBanner;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\File $websiteBannerImage
     */
    public function setWebsiteBannerImage(File $websiteBannerImage = null)
    {
        $this->websiteBannerImage = $websiteBannerImage;

        if ($websiteBannerImage) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getWebsiteBannerImage()
    {
        return $this->websiteBannerImage;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Set websiteBrand
     *
     * @param string $websiteBrand
     * @return Settings
     */
    public function setWebsiteBrand($websiteBrand)
    {
        $this->websiteBrand = $websiteBrand;

        return $this;
    }

    /**
     * Get websiteBrand
     *
     * @return string 
     */
    public function getWebsiteBrand()
    {
        return $this->websiteBrand;
    }

    /**
     * Set websiteName
     *
     * @param string $websiteName
     * @return Settings
     */
    public function setWebsiteName($websiteName)
    {
        $this->websiteName = $websiteName;

        return $this;
    }

    /**
     * Get websiteName
     *
     * @return string 
     */
    public function getWebsiteName()
    {
        return $this->websiteName;
    }

    /**
     * Set websiteLastname
     *
     * @param string $websiteLastname
     * @return Settings
     */
    public function setWebsiteLastname($websiteLastname)
    {
        $this->websiteLastname = $websiteLastname;

        return $this;
    }

    /**
     * Get websiteLastname
     *
     * @return string 
     */
    public function getWebsiteLastname()
    {
        return $this->websiteLastname;
    }

    /**
     * Set websiteSlogan
     *
     * @param string $websiteSlogan
     * @return Settings
     */
    public function setWebsiteSlogan($websiteSlogan)
    {
        $this->websiteSlogan = $websiteSlogan;

        return $this;
    }

    /**
     * Get websiteSlogan
     *
     * @return string 
     */
    public function getWebsiteSlogan()
    {
        return $this->websiteSlogan;
    }

    /**
     * Set websiteCopyright
     *
     * @param string $websiteCopyright
     * @return Settings
     */
    public function setWebsiteCopyright($websiteCopyright)
    {
        $this->websiteCopyright = $websiteCopyright;

        return $this;
    }

    /**
     * Get websiteCopyright
     *
     * @return string 
     */
    public function getWebsiteCopyright()
    {
        return $this->websiteCopyright;
    }

    /**
     * Set websiteAddress
     *
     * @param string $websiteAddress
     * @return Settings
     */
    public function setWebsiteAddress($websiteAddress)
    {
        $this->websiteAddress = $websiteAddress;

        return $this;
    }

    /**
     * Get websiteAddress
     *
     * @return string 
     */
    public function getWebsiteAddress()
    {
        return $this->websiteAddress;
    }

    /**
     * Set websitePhone
     *
     * @param string $websitePhone
     * @return Settings
     */
    public function setWebsitePhone($websitePhone)
    {
        $this->websitePhone = $websitePhone;

        return $this;
    }

    /**
     * Get websitePhone
     *
     * @return string 
     */
    public function getWebsitePhone()
    {
        return $this->websitePhone;
    }

    /**
     * Set websiteFax
     *
     * @param string $websiteFax
     * @return Settings
     */
    public function setWebsiteFax($websiteFax)
    {
        $this->websiteFax = $websiteFax;

        return $this;
    }

    /**
     * Get websiteFax
     *
     * @return string 
     */
    public function getWebsiteFax()
    {
        return $this->websiteFax;
    }

    /**
     * Set websiteEmail
     *
     * @param string $websiteEmail
     * @return Settings
     */
    public function setWebsiteEmail($websiteEmail)
    {
        $this->websiteEmail = $websiteEmail;

        return $this;
    }

    /**
     * Get websiteEmail
     *
     * @return string 
     */
    public function getWebsiteEmail()
    {
        return $this->websiteEmail;
    }

    /**
     * Set websiteUrlTwitter
     *
     * @param string $websiteUrlTwitter
     * @return Settings
     */
    public function setWebsiteUrlTwitter($websiteUrlTwitter)
    {
        $this->websiteUrlTwitter = $websiteUrlTwitter;

        return $this;
    }

    /**
     * Get websiteUrlTwitter
     *
     * @return string 
     */
    public function getWebsiteUrlTwitter()
    {
        return $this->websiteUrlTwitter;
    }

    /**
     * Set websiteUrlGoogle
     *
     * @param string $websiteUrlGoogle
     * @return Settings
     */
    public function setWebsiteUrlGoogle($websiteUrlGoogle)
    {
        $this->websiteUrlGoogle = $websiteUrlGoogle;

        return $this;
    }

    /**
     * Get websiteUrlGoogle
     *
     * @return string 
     */
    public function getWebsiteUrlGoogle()
    {
        return $this->websiteUrlGoogle;
    }

    /**
     * Set websiteUrlFacebook
     *
     * @param string $websiteUrlFacebook
     * @return Settings
     */
    public function setWebsiteUrlFacebook($websiteUrlFacebook)
    {
        $this->websiteUrlFacebook = $websiteUrlFacebook;

        return $this;
    }

    /**
     * Get websiteUrlFacebook
     *
     * @return string 
     */
    public function getWebsiteUrlFacebook()
    {
        return $this->websiteUrlFacebook;
    }

    /**
     * Set websiteUrlFlickr
     *
     * @param string $websiteUrlFlickr
     * @return Settings
     */
    public function setWebsiteUrlFlickr($websiteUrlFlickr)
    {
        $this->websiteUrlFlickr = $websiteUrlFlickr;

        return $this;
    }

    /**
     * Get websiteUrlFlickr
     *
     * @return string 
     */
    public function getWebsiteUrlFlickr()
    {
        return $this->websiteUrlFlickr;
    }

    /**
     * Set websiteUrlInstagram
     *
     * @param string $websiteUrlInstagram
     * @return Settings
     */
    public function setWebsiteUrlInstagram($websiteUrlInstagram)
    {
        $this->websiteUrlInstagram = $websiteUrlInstagram;

        return $this;
    }

    /**
     * Get websiteUrlInstagram
     *
     * @return string 
     */
    public function getWebsiteUrlInstagram()
    {
        return $this->websiteUrlInstagram;
    }

    /**
     * Set headerFeatureBrands
     *
     * @param string $headerFeatureBrands
     * @return Settings
     */
    public function setHeaderFeatureBrands($headerFeatureBrands)
    {
        $this->headerFeatureBrands = $headerFeatureBrands;

        return $this;
    }

    /**
     * Get headerFeatureBrands
     *
     * @return string 
     */
    public function getHeaderFeatureBrands()
    {
        return $this->headerFeatureBrands;
    }

    /**
     * Set subheaderFeatureBrands
     *
     * @param string $subheaderFeatureBrands
     * @return Settings
     */
    public function setSubheaderFeatureBrands($subheaderFeatureBrands)
    {
        $this->subheaderFeatureBrands = $subheaderFeatureBrands;

        return $this;
    }

    /**
     * Get subheaderFeatureBrands
     *
     * @return string 
     */
    public function getSubheaderFeatureBrands()
    {
        return $this->subheaderFeatureBrands;
    }

    /**
     * Set headerBecomeMemberToday
     *
     * @param string $headerBecomeMemberToday
     * @return Settings
     */
    public function setHeaderBecomeMemberToday($headerBecomeMemberToday)
    {
        $this->headerBecomeMemberToday = $headerBecomeMemberToday;

        return $this;
    }

    /**
     * Get headerBecomeMemberToday
     *
     * @return string 
     */
    public function getHeaderBecomeMemberToday()
    {
        return $this->headerBecomeMemberToday;
    }

    /**
     * Set subheaderBecomeMemberToday
     *
     * @param string $subheaderBecomeMemberToday
     * @return Settings
     */
    public function setSubheaderBecomeMemberToday($subheaderBecomeMemberToday)
    {
        $this->subheaderBecomeMemberToday = $subheaderBecomeMemberToday;

        return $this;
    }

    /**
     * Get subheaderBecomeMemberToday
     *
     * @return string 
     */
    public function getSubheaderBecomeMemberToday()
    {
        return $this->subheaderBecomeMemberToday;
    }

    /**
     * Set headerTodaysHoursOperation
     *
     * @param string $headerTodaysHoursOperation
     * @return Settings
     */
    public function setHeaderTodaysHoursOperation($headerTodaysHoursOperation)
    {
        $this->headerTodaysHoursOperation = $headerTodaysHoursOperation;

        return $this;
    }

    /**
     * Get headerTodaysHoursOperation
     *
     * @return string 
     */
    public function getHeaderTodaysHoursOperation()
    {
        return $this->headerTodaysHoursOperation;
    }

    /**
     * Set subheaderTodaysHoursOperation
     *
     * @param string $subheaderTodaysHoursOperation
     * @return Settings
     */
    public function setSubheaderTodaysHoursOperation($subheaderTodaysHoursOperation)
    {
        $this->subheaderTodaysHoursOperation = $subheaderTodaysHoursOperation;

        return $this;
    }

    /**
     * Get subheaderTodaysHoursOperation
     *
     * @return string 
     */
    public function getSubheaderTodaysHoursOperation()
    {
        return $this->subheaderTodaysHoursOperation;
    }

    /**
     * Set headerTopDealsOfDay
     *
     * @param string $headerTopDealsOfDay
     * @return Settings
     */
    public function setHeaderTopDealsOfDay($headerTopDealsOfDay)
    {
        $this->headerTopDealsOfDay = $headerTopDealsOfDay;

        return $this;
    }

    /**
     * Get headerTopDealsOfDay
     *
     * @return string 
     */
    public function getHeaderTopDealsOfDay()
    {
        return $this->headerTopDealsOfDay;
    }

    /**
     * Set subheaderTopDealsOfDay
     *
     * @param string $subheaderTopDealsOfDay
     * @return Settings
     */
    public function setSubheaderTopDealsOfDay($subheaderTopDealsOfDay)
    {
        $this->subheaderTopDealsOfDay = $subheaderTopDealsOfDay;

        return $this;
    }

    /**
     * Get subheaderTopDealsOfDay
     *
     * @return string 
     */
    public function getSubheaderTopDealsOfDay()
    {
        return $this->subheaderTopDealsOfDay;
    }

    /**
     * Set headerContactUs
     *
     * @param string $headerContactUs
     * @return Settings
     */
    public function setHeaderContactUs($headerContactUs)
    {
        $this->headerContactUs = $headerContactUs;

        return $this;
    }

    /**
     * Get headerContactUs
     *
     * @return string 
     */
    public function getHeaderContactUs()
    {
        return $this->headerContactUs;
    }

    /**
     * Set subheaderContactUs
     *
     * @param string $subheaderContactUs
     * @return Settings
     */
    public function setSubheaderContactUs($subheaderContactUs)
    {
        $this->subheaderContactUs = $subheaderContactUs;

        return $this;
    }

    /**
     * Get subheaderContactUs
     *
     * @return string 
     */
    public function getSubheaderContactUs()
    {
        return $this->subheaderContactUs;
    }

    /**
     * Set headerBusinessActiveDeals
     *
     * @param string $headerBusinessActiveDeals
     * @return Settings
     */
    public function setHeaderBusinessActiveDeals($headerBusinessActiveDeals)
    {
        $this->headerBusinessActiveDeals = $headerBusinessActiveDeals;

        return $this;
    }

    /**
     * Get headerBusinessActiveDeals
     *
     * @return string 
     */
    public function getHeaderBusinessActiveDeals()
    {
        return $this->headerBusinessActiveDeals;
    }

    /**
     * Set subheaderBusinessActiveDeals
     *
     * @param string $subheaderBusinessActiveDeals
     * @return Settings
     */
    public function setSubheaderBusinessActiveDeals($subheaderBusinessActiveDeals)
    {
        $this->subheaderBusinessActiveDeals = $subheaderBusinessActiveDeals;

        return $this;
    }

    /**
     * Get subheaderBusinessActiveDeals
     *
     * @return string 
     */
    public function getSubheaderBusinessActiveDeals()
    {
        return $this->subheaderBusinessActiveDeals;
    }

    /**
     * Set headerBusinessRelatedBusinesses
     *
     * @param string $headerBusinessRelatedBusinesses
     * @return Settings
     */
    public function setHeaderBusinessRelatedBusinesses($headerBusinessRelatedBusinesses)
    {
        $this->headerBusinessRelatedBusinesses = $headerBusinessRelatedBusinesses;

        return $this;
    }

    /**
     * Get headerBusinessRelatedBusinesses
     *
     * @return string 
     */
    public function getHeaderBusinessRelatedBusinesses()
    {
        return $this->headerBusinessRelatedBusinesses;
    }

    /**
     * Set subheaderBusinessRelatedBusinesses
     *
     * @param string $subheaderBusinessRelatedBusinesses
     * @return Settings
     */
    public function setSubheaderBusinessRelatedBusinesses($subheaderBusinessRelatedBusinesses)
    {
        $this->subheaderBusinessRelatedBusinesses = $subheaderBusinessRelatedBusinesses;

        return $this;
    }

    /**
     * Get subheaderBusinessRelatedBusinesses
     *
     * @return string 
     */
    public function getSubheaderBusinessRelatedBusinesses()
    {
        return $this->subheaderBusinessRelatedBusinesses;
    }

    /**
     * Set headerLogin
     *
     * @param string $headerLogin
     * @return Settings
     */
    public function setHeaderLogin($headerLogin)
    {
        $this->headerLogin = $headerLogin;

        return $this;
    }

    /**
     * Get headerLogin
     *
     * @return string 
     */
    public function getHeaderLogin()
    {
        return $this->headerLogin;
    }

    /**
     * Set textLogin
     *
     * @param string $textLogin
     * @return Settings
     */
    public function setTextLogin($textLogin)
    {
        $this->textLogin = $textLogin;

        return $this;
    }

    /**
     * Get textLogin
     *
     * @return string 
     */
    public function getTextLogin()
    {
        return $this->textLogin;
    }

    /**
     * Set headerAbout
     *
     * @param string $headerAbout
     * @return Settings
     */
    public function setHeaderAbout($headerAbout)
    {
        $this->headerAbout = $headerAbout;

        return $this;
    }

    /**
     * Get headerAbout
     *
     * @return string 
     */
    public function getHeaderAbout()
    {
        return $this->headerAbout;
    }

    /**
     * Set subheaderAbout
     *
     * @param string $subheaderAbout
     * @return Settings
     */
    public function setSubheaderAbout($subheaderAbout)
    {
        $this->subheaderAbout = $subheaderAbout;

        return $this;
    }

    /**
     * Get subheaderAbout
     *
     * @return string 
     */
    public function getSubheaderAbout()
    {
        return $this->subheaderAbout;
    }

    /**
     * Set textAbout
     *
     * @param string $textAbout
     * @return Settings
     */
    public function setTextAbout($textAbout)
    {
        $this->textAbout = $textAbout;

        return $this;
    }

    /**
     * Get textAbout
     *
     * @return string 
     */
    public function getTextAbout()
    {
        return $this->textAbout;
    }

    /**
     * Set headerSubscribeForDeals
     *
     * @param string $headerSubscribeForDeals
     * @return Settings
     */
    public function setHeaderSubscribeForDeals($headerSubscribeForDeals)
    {
        $this->headerSubscribeForDeals = $headerSubscribeForDeals;

        return $this;
    }

    /**
     * Get headerSubscribeForDeals
     *
     * @return string 
     */
    public function getHeaderSubscribeForDeals()
    {
        return $this->headerSubscribeForDeals;
    }

    /**
     * Set subheaderSubscribeForDeals
     *
     * @param string $subheaderSubscribeForDeals
     * @return Settings
     */
    public function setSubheaderSubscribeForDeals($subheaderSubscribeForDeals)
    {
        $this->subheaderSubscribeForDeals = $subheaderSubscribeForDeals;

        return $this;
    }

    /**
     * Get subheaderSubscribeForDeals
     *
     * @return string 
     */
    public function getSubheaderSubscribeForDeals()
    {
        return $this->subheaderSubscribeForDeals;
    }

    /**
     * Set textSubscribeForDeals
     *
     * @param string $textSubscribeForDeals
     * @return Settings
     */
    public function setTextSubscribeForDeals($textSubscribeForDeals)
    {
        $this->textSubscribeForDeals = $textSubscribeForDeals;

        return $this;
    }

    /**
     * Get textSubscribeForDeals
     *
     * @return string 
     */
    public function getTextSubscribeForDeals()
    {
        return $this->textSubscribeForDeals;
    }

    /**
     * Set headerStayClose
     *
     * @param string $headerStayClose
     * @return Settings
     */
    public function setHeaderStayClose($headerStayClose)
    {
        $this->headerStayClose = $headerStayClose;

        return $this;
    }

    /**
     * Get headerStayClose
     *
     * @return string 
     */
    public function getHeaderStayClose()
    {
        return $this->headerStayClose;
    }

    /**
     * Set subheaderStayClose
     *
     * @param string $subheaderStayClose
     * @return Settings
     */
    public function setSubheaderStayClose($subheaderStayClose)
    {
        $this->subheaderStayClose = $subheaderStayClose;

        return $this;
    }

    /**
     * Get subheaderStayClose
     *
     * @return string 
     */
    public function getSubheaderStayClose()
    {
        return $this->subheaderStayClose;
    }
}
