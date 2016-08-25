<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Deal class
 *
 * @package \BackendBundle\Entity
 *
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\DealRepository")
 * @ORM\Table(name="deal")
 */
class Deal
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
     * @Assert\Length(min="3", minMessage="The name must have 3 characters or more")
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @Slug(fields={"name"})
     * @ORM\Column(type="string", unique=true, length=128)
     */
    private $slug;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="text")
     * @Assert\Length(min="3", minMessage="The description must have 3 characters or more")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $startsAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $endsAt;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @var File
     *
     * @Vich\UploadableField(mapping="deal_image", fileNameProperty="image",
     *      groups={"creation"}
     * )
     * @Assert\Image(
     *     minWidth="16",
     *     maxWidth="1500",
     *     minWidthMessage="The image must have a with between 16 and 1500 pixels",
     *     maxWidthMessage="The image must have a with between 16 and 1500 pixels",
     *     minHeight="16",
     *     maxHeight="1500",
     *     minHeightMessage="The image must have a height between 16 and 1500 pixels",
     *     maxHeightMessage="The image must have a height between 16 and 1500 pixels",
     *     maxSize="5M",
     *     maxSizeMessage="The image must have 5 MB (megabytes) or less"
     * )
     */
    private $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Url()
     */
    private $video;

    /**
     * @var integer
     *
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *     pattern="/^\-?\d+$/",
     *     message="The points must be a valid number"
     * )
     */
    private $points;

    /**
     * @var Business
     *
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="deals")
     */
    private $business;

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
        return
            strtoupper($this->name)
            . " | " . $this->points . " Points | "
            . $this->startsAt->format("m/d/Y")
            . " - "
            . $this->endsAt->format("m/d/Y");
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime
     */
    public function getStartsAt()
    {
        return $this->startsAt;
    }

    /**
     * @param \DateTime $startsAt
     */
    public function setStartsAt($startsAt)
    {
        $this->startsAt = $startsAt;
    }

    /**
     * @return \DateTime
     */
    public function getEndsAt()
    {
        return $this->endsAt;
    }

    /**
     * @param \DateTime $endsAt
     */
    public function setEndsAt($endsAt)
    {
        $this->endsAt = $endsAt;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param string $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @param Business $business
     */
    public function setBusiness($business)
    {
        $this->business = $business;
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

    public function isActive()
    {
        return $this->isActive;
    }

    public function isExpired()
    {
        if (false === $this->isActive) {
            return true;
        }

        return (null !== $this->endsAt && $this->endsAt->getTimestamp() < time());
    }

    /**
     * @param $status
     *
     * @return bool
     */
    public function setIsActive($status)
    {
        $this->isActive = $status;
    }

    /**
     * Toggle the status of the deal
     */
    public function toggle()
    {
        $this->isActive = !$this->isActive;
    }

    /**
     * Enable the deal
     */
    public function enable()
    {
        $this->isActive = true;
    }

    /**
     * Disable the deal
     */
    public function disable()
    {
        $this->isActive = false;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}