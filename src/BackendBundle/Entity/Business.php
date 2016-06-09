<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Business class
 *
 * @Vich\Uploadable
 * @ORM\Table(name="business")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\BusinessRepository")
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\Length(min="3", minMessage="The name must have 3 characters or more")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\Length(min="3", minMessage="The description must have 3 characters or more")
     */
    private $description;

    /**
     * @var string
     *
     * @Slug(fields={"name"})
     * @ORM\Column(type="string", unique=true, length=128)
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
     * @Assert\Regex(
     *     pattern="/^(\(\+\d{1,3}\)|\+\d{1,3})?[\040\-]?\d?([\040\-]?\d{3,4}){2,4}$/",
     *     message="Enter a valid phone number please"
     * )
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255)
     */
    private $logo;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
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
     *     maxHeightMessage=""The logo must have a height between 16 and 1500 pixels",
     *     maxSize="5M",
     *     maxSizeMessage="The logo must have 5 MB (megabytes) or less",
     *     mimeTypes="{'image/jpg','image/jpeg','image/png',image/bmp}",
     *     mimeTypesMessage="The logo image should be a JPG, JPEG, PNG or BMP image"
     * )
     * @var File
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

    public function __construct()
    {
        $this->createdAt = new \DateTime();

        if (null === $this->updatedAt) {
            $this->updatedAt = new \DateTime();
        }

        $this->categories = new ArrayCollection();
        $this->customers = new ArrayCollection();
    }

    function __toString()
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
}
