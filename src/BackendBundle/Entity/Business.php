<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Business class
 *
 * @Vich\Uploadable
 * @ORM\Table(name="business")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\BusinessRepository")
 * @DoctrineAssert\UniqueEntity(
 *     fields={"name"},
 *     errorPath="name",
 *     message="Already exist a Business with this name"
 * )
 */
class Business
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(min="3", minMessage="The name must have 3 characters or more")
     * @Groups({"group1", "map"})
     * @Gedmo\Translatable
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\Length(min="3", minMessage="The description must have 3 characters or more")
     * @Gedmo\Translatable
     */
    private $description;

    /**
     * @var string
     *
     * @Slug(fields={"name"})
     * @ORM\Column(type="string", unique=true, length=128)
     * @Gedmo\Translatable
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     * @Assert\Length(
     *     min="6",
     *     max="20",
     *     minMessage="The phone must have between 6 and 20 characters",
     *     maxMessage="The phone must have between 6 and 20 characters"
     * )
     * )
     */
    private $phone;

    /**
     * @ORM\Column(name="hours_begin", type="time")
     *
     * @Assert\Time()
     */
    private $hoursBegin;

    /**
     * @ORM\Column(name="hours_end", type="time")
     *
     * @Assert\Time()
     */
    private $hoursEnd;

    /**
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     *
     * @Assert\Url()
     */
    private $website;

    /**
     * @ORM\Column(name="email", type="string", length=255)
     *
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(name="notify_email", type="string", length=255, nullable=true)
     */
    private $notifyEmails;

    /**
     * @ORM\Column(name="mall_map_directions", type="string", nullable=true)
     * @Gedmo\Translatable
     */
    private $mallMapDirections;

    /**
     * @ORM\Column(name="mall_map_x", type="string", nullable=true)
     */
    private $mallMapX;

    /**
     * @ORM\Column(name="mall_map_y", type="string", nullable=true)
     */
    private $mallMapY;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255)
     */
    private $logo;

    /**
     * @var File
     *
     * This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="business_image", fileNameProperty="logo",
     *      groups={"creation"}
     * )
     * @Assert\Image(
     *     minWidth="16",
     *     maxWidth="1500",
     *     minWidthMessage="The logo must have a with between 16 and 1500 pixels",
     *     maxWidthMessage="The logo must have a with between 16 and 1500 pixels",
     *     minHeight="16",
     *     maxHeight="1500",
     *     minHeightMessage="The logo must have a height between 16 and 1500 pixels",
     *     maxHeightMessage="The logo must have a height between 16 and 1500 pixels",
     *     maxSize="1M",
     *     maxSizeMessage="The logo must have 1 MB (megabytes) or less"
     * )
     */
    private $logoFile;

    /**
     * @var string
     *
     * @ORM\Column(name="social_media", type="string", length=255)
     * @Assert\Url()
     */
    private $socialMedia;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram", type="string", length=255, nullable=true)
     * @Assert\Url()
     */
    private $instagram;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     * @Assert\Url()
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="pinterest", type="string", length=255, nullable=true)
     * @Assert\Url()
     */
    private $pinterest;

    /**
     * @var string
     *
     * @ORM\Column(name="snapchat", type="string", length=255, nullable=true)
     * @Assert\Url()
     */
    private $snapchat;

    /**
     * @var string
     *
     * @ORM\Column(name="ios_app", type="string", length=255, nullable=true)
     * @Assert\Url()
     */
    private $iosApp;

    /**
     * @var string
     *
     * @ORM\Column(name="android_app", type="string", length=255, nullable=true)
     * @Assert\Url()
     */
    private $androidApp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="businesses")
     */
    private $categories;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\Deal", mappedBy="business",
     *     cascade={"persist", "remove", "merge"}
     * )
     */
    private $deals;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="SystemUser", mappedBy="business")
     */
    private $customers;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\Opening", mappedBy="business",
     *     cascade={"persist", "remove", "merge"}
     * )
     */
    private $openings;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isPublic = true;

    public function __construct()
    {
        $this->createdAt = new \DateTime();

        if (null === $this->updatedAt) {
            $this->updatedAt = new \DateTime();
        }

        $this->categories = new ArrayCollection();
        $this->customers = new ArrayCollection();
        $this->openings = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }


    /**
     * Adds, if not already defined a category to the business
     *
     * @param \BackendBundle\Entity\Category $category
     */
    public function addCategory(Category $category)
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }
    }

    /**
     * Remove, if exists, a category from the associated categories
     *
     * @param \BackendBundle\Entity\Category $category
     */
    public function removeCategory(Category $category)
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }
    }

    /**
     * Add a deal to this business
     *
     * @param \BackendBundle\Entity\Deal $deal
     */
    public function addDeal(Deal $deal)
    {
        if (!$this->deals->contains($deal)) {
            $this->deals->add($deal);
        }
    }

    /**
     * Remove, if exists, a deal from this business
     *
     * @param \BackendBundle\Entity\Deal $deal
     */
    public function removeDeal(Deal $deal)
    {
        if ($this->deals->contains($deal)) {
            $this->deals->removeElement($deal);
        }
    }

    /**
     * Add an opening to this business
     *
     * @param \BackendBundle\Entity\Opening $opening
     */
    public function addOpening(Opening $opening)
    {
        if (!$this->openings->contains($opening)) {
            $this->openings->add($opening);
        }
    }

    /**
     * Remove, if exists, an opening from this business
     *
     * @param \BackendBundle\Entity\Opening $opening
     */
    public function removeOpening(Opening $opening)
    {
        if ($this->openings->contains($opening)) {
            $this->openings->removeElement($opening);
        }
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Business
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Business
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Business
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set hoursBegin
     *
     * @param \DateTime $hoursBegin
     * @return Business
     */
    public function setHoursBegin($hoursBegin)
    {
        $this->hoursBegin = $hoursBegin;

        return $this;
    }

    /**
     * Get hoursBegin
     *
     * @return \DateTime
     */
    public function getHoursBegin()
    {
        return $this->hoursBegin;
    }

    /**
     * Set hoursEnd
     *
     * @param \DateTime $hoursEnd
     * @return Business
     */
    public function setHoursEnd($hoursEnd)
    {
        $this->hoursEnd = $hoursEnd;

        return $this;
    }

    /**
     * Get hoursEnd
     *
     * @return \DateTime
     */
    public function getHoursEnd()
    {
        return $this->hoursEnd;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Business
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Business
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set mallMapDirections
     *
     * @param string $mallMapDirections
     * @return Business
     */
    public function setMallMapDirections($mallMapDirections)
    {
        $this->mallMapDirections = $mallMapDirections;

        return $this;
    }

    /**
     * Get mallMapDirections
     *
     * @return string
     */
    public function getMallMapDirections()
    {
        return $this->mallMapDirections;
    }

    /**
     * Set mallMapX
     *
     * @param string $mallMapX
     * @return Business
     */
    public function setMallMapX($mallMapX)
    {
        $this->mallMapX = $mallMapX;

        return $this;
    }

    /**
     * Get mallMapX
     *
     * @return string
     */
    public function getMallMapX()
    {
        return $this->mallMapX;
    }

    /**
     * Set mallMapY
     *
     * @param string $mallMapY
     * @return Business
     */
    public function setMallMapY($mallMapY)
    {
        $this->mallMapY = $mallMapY;

        return $this;
    }

    /**
     * Get mallMapY
     *
     * @return string
     */
    public function getMallMapY()
    {
        return $this->mallMapY;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Business
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\File $image
     */
    public function setLogoFile(File $image = null)
    {
        $this->logoFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * @return string
     */
    public function getSocialMedia()
    {
        return $this->socialMedia;
    }

    /**
     * @param string $socialMedia
     */
    public function setSocialMedia($socialMedia)
    {
        $this->socialMedia = $socialMedia;
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

    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return ArrayCollection
     */
    public function getDeals()
    {
        return $this->deals;
    }

    /**
     * @param ArrayCollection $deals
     */
    public function setDeals($deals)
    {
        $this->deals = $deals;
    }

    /**
     * Add customers
     *
     * @param SystemUser $customers
     * @return Business
     */
    public function addCustomer(SystemUser $customers)
    {
        if (!$this->customers->contains($customers)) {
            $this->customers->add($customers);
        }
        return $this;
    }

    /**
     * Remove customers
     *
     * @param SystemUser $customers
     */
    public function removeCustomer(SystemUser $customers)
    {
        if ($this->customers->contains($customers)) {
            $this->customers->removeElement($customers);
        }
    }

    /**
     * Get customers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * @param ArrayCollection $customers
     */
    public function setCustomers($customers)
    {
        $this->customers = $customers;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Set the translatable locale to refresh the entity
     *
     * @param $locale   string  Locale code
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Set iosApp
     *
     * @param string $iosApp
     * @return Business
     */
    public function setIosApp($iosApp)
    {
        $this->iosApp = $iosApp;

        return $this;
    }

    /**
     * Get iosApp
     *
     * @return string 
     */
    public function getIosApp()
    {
        return $this->iosApp;
    }

    /**
     * Set androidApp
     *
     * @param string $androidApp
     * @return Business
     */
    public function setAndroidApp($androidApp)
    {
        $this->androidApp = $androidApp;

        return $this;
    }

    /**
     * Get androidApp
     *
     * @return string 
     */
    public function getAndroidApp()
    {
        return $this->androidApp;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getOpenings()
    {
        return $this->openings;
    }

    public function isPublic()
    {
        return $this->isPublic;
    }

    public function setIsPublic($value)
    {
        $this->isPublic = $value;
    }

    public function getNotifyEmails()
    {
        return $this->notifyEmails;
    }

    public function setNotifyEmails($emails)
    {
        $this->notifyEmails = $emails;
    }

    /**
     * Set instagram
     *
     * @param string $instagram
     * @return Business
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * Get instagram
     *
     * @return string 
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return Business
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set pinterest
     *
     * @param string $pinterest
     * @return Business
     */
    public function setPinterest($url)
    {
        $this->pinterest = $url;

        return $this;
    }

    /**
     * Get pinterest
     *
     * @return string 
     */
    public function getPinterest()
    {
        return $this->pinterest;
    }

    /**
     * Set snapchat
     *
     * @param string $snapchat
     * @return Business
     */
    public function setSnapchat($url)
    {
        $this->snapchat = $url;

        return $this;
    }

    /**
     * Get snapchat
     *
     * @return string 
     */
    public function getSnapchat()
    {
        return $this->snapchat;
    }

    /**
     * Get isPublic
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }
}
