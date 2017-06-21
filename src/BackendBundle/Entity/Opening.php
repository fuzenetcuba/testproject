<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Represents an opening job in a given position for a business
 *
 * @package \BackendBundle\Entity
 * @ORM\Entity()
 * @DoctrineAssert\UniqueEntity(
 *     fields={"position", "business"},
 *     errorPath="business",
 *     message="Already exist this opening for this Business"
 * )
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
     * @Gedmo\Slug(fields={"position"}, handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="relationField", value="business"),
     *          @Gedmo\SlugHandlerOption(name="relationSlugField", value="id"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="-"),
     *          @Gedmo\SlugHandlerOption(name="urilize", value=true)
     *      })
     * })
     * @ORM\Column(type="string", unique=true, length=128)
     * @Gedmo\Translatable
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Translatable
     */
    private $department;

    /**
     * @var Business
     *
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="openings")
     */
    private $business;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="BackendBundle\Entity\OpeningCategory", inversedBy="openings")
     */
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     * @Gedmo\Translatable
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\Candidate", mappedBy="opening",
     *     cascade={"persist", "remove", "merge"}
     * )
     */
    private $candidates;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    private $locale;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->candidates = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->position;
    }


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


    /**
     * Adds, if not already defined a category to the business
     *
     * @param \BackendBundle\Entity\OpeningCategory $category
     */
    public function addCategory(OpeningCategory $category)
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }
    }

    /**
     * Remove, if exists, a category from the associated categories
     *
     * @param \BackendBundle\Entity\OpeningCategory $category
     */
    public function removeCategory(OpeningCategory $category)
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return Opening
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Toggle the status of the opening
     */
    public function toggle()
    {
        $this->enabled = !$this->enabled;
    }

    /**
     * Add candidates
     *
     * @param \BackendBundle\Entity\Candidate $candidates
     * @return Opening
     */
    public function addCandidate(\BackendBundle\Entity\Candidate $candidates)
    {
        $this->candidates[] = $candidates;

        return $this;
    }

    /**
     * Remove candidates
     *
     * @param \BackendBundle\Entity\Candidate $candidates
     */
    public function removeCandidate(\BackendBundle\Entity\Candidate $candidates)
    {
        $this->candidates->removeElement($candidates);
    }

    /**
     * Get candidates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCandidates()
    {
        return $this->candidates;
    }
}
