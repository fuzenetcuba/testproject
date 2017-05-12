<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Represents an image resource for a business
 *
 * @package \BackendBundle\Entity
 *
 * @ORM\Entity()
 * @Vich\Uploadable
 * @DoctrineAssert\UniqueEntity(
 *     fields={"imgName"},
 *     errorPath="imgName",
 *     message="Already exist an image with this name"
 * )
 */
class Image
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
     * @ORM\Column(name="img_name", type="string", length=255)
     */
    private $imgName;

    /**
     * @var File
     *
     * This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="business_gallery_image", fileNameProperty="imgName",
     *      groups={"creation"}
     * )
     * @Assert\Image(
     *     minWidth="16",
     *     maxWidth="2500",
     *     minWidthMessage="The image must have a with between 16 and 2500 pixels",
     *     maxWidthMessage="The image must have a with between 16 and 2500 pixels",
     *     minHeight="16",
     *     maxHeight="2500",
     *     minHeightMessage="The image must have a height between 16 and 2500 pixels",
     *     maxHeightMessage="The image must have a height between 16 and 2500 pixels",
     *     maxSize="5M",
     *     maxSizeMessage="The image must have 5 MB (megabytes) or less"
     * )
     */
    private $imgFile;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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
     * @var Business
     *
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="images")
     */
    private $business;

    public function __construct()
    {
        $this->createdAt = new \DateTime();

        if (null === $this->updatedAt) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function __toString()
    {
        return $this->imgName;
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
     * Set imgName
     *
     * @param string $imgName
     * @return Image
     */
    public function setImgName($imgName)
    {
        $this->imgName = $imgName;

        return $this;
    }

    /**
     * Get imgName
     *
     * @return string 
     */
    public function getImgName()
    {
        return $this->imgName;
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
    public function setImgFile(File $image = null)
    {
        $this->imgFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImgFile()
    {
        return $this->imgFile;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Image
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Image
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * Set business
     *
     * @param \BackendBundle\Entity\Business $business
     * @return Image
     */
    public function setBusiness(\BackendBundle\Entity\Business $business = null)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * Get business
     *
     * @return \BackendBundle\Entity\Business 
     */
    public function getBusiness()
    {
        return $this->business;
    }
}
