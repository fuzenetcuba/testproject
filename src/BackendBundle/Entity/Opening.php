<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Represents an opening job in a given position for a business
 *
 * @package \BackendBundle\Entity
 * @ORM\Entity
 */
class Opening
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
     * @ORM\Column(type="string")
     * @Gedmo\Translatable
     */
    private $position;

    /**
     * @var string
     *
     * @Gedmo\Translatable)
     * @Gedmo\Slug(fields={"position"})
     * @ORM\Column(type="string", unique=true, length=128)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Gedmo\Translatable
     */
    // private $department;

    /**
     * @var Business
     *
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="openings")
     */
    private $business;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     * @Gedmo\Translatable
     */
    private $description;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
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
     * @return \BackendBundle\Entity\Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @param \BackendBundle\Entity\Business $business
     */
    public function setBusiness($business)
    {
        $this->business = $business;
    }

    /**
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param string $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}