<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="helpers")
 * @Vich\Uploadable
 */
class Helpers {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * Date
     * 
     * @ORM\Column(type="date", nullable=true)      
     */
    private $date;
    
    /**    
     * Time
     * 
     * @ORM\Column(type="time", nullable=true)      
     */
    private $time;
    
    /**
     * DateTime
     * 
     * @ORM\Column(type="datetime", nullable=true)      
     */
    private $dateTime;
    
    /**
     * Range of Date
     * 
     * @ORM\Column(type="string", nullable=true)      
     */
    private $dateRange;
    
    /**
     * Range of Time
     * 
     * @ORM\Column(type="string", nullable=true)      
     */
    private $timeRange;
    
    /**     
     * @ORM\Column(type="boolean", nullable=true)      
     */
    private $isActive;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Assert\Image(
     *      maxSize="5M",
     *      mimeTypes={"image/jpg", "image/jpeg", "image/png"},
     *      minWidth=150,
     *      minHeight=150,
     *      maxWidth=2500,
     *      maxHeight=2500,
     * )
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="imageName",
     *      groups={"creation"}
     * )
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\SystemUser")
     * @ORM\JoinColumn(name="person_user_id", referencedColumnName="id")
     */
    private $person;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Helpers
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Helpers
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     *
     * @return Helpers
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set dateRange
     *
     * @param string $dateRange
     *
     * @return Helpers
     */
    public function setDateRange($dateRange)
    {
        $this->dateRange = $dateRange;

        return $this;
    }

    /**
     * Get dateRange
     *
     * @return string
     */
    public function getDateRange()
    {
        return $this->dateRange;
    }

    /**
     * Set timeRange
     *
     * @param string $timeRange
     *
     * @return Helpers
     */
    public function setTimeRange($timeRange)
    {
        $this->timeRange = $timeRange;

        return $this;
    }

    /**
     * Get timeRange
     *
     * @return string
     */
    public function getTimeRange()
    {
        return $this->timeRange;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Helpers
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImageFile(File $image = null) {
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
    public function getImageFile() {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     */
    public function setImageName($imageName) {
        $this->imageName = $imageName;
    }

    /**
     * @return string
     */
    public function getImageName() {
        return $this->imageName;
    }

    /**
     * @return File
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * @param string $imageName
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Set person
     *
     * @param SystemUser $person
     *
     * @return Helpers
     */
    public function setPerson(SystemUser $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return SystemUser
     */
    public function getPerson()
    {
        return $this->person;
    }
}
