<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 *
 * @package \BackendBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="category")
 */
class Category
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
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Business", mappedBy="categories")
     */
    private $businesses;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     */
    private $parent;

    public function __construct()
    {
        $this->businesses = new ArrayCollection();
        $this->children   = new ArrayCollection();
    }

    function __toString()
    {
        return $this->name;
    }


    /**
     * Adds a bussiness to this category, for keeping the bidirectional
     * relation
     *
     * @param \BackendBundle\Entity\Business $business
     */
    public function addBusiness(Business $business)
    {
        if (!$this->businesses->contains($business)) {
            $this->businesses->add($business);
        }
    }

    /**
     * Remove bussiness from this category.
     *
     * @param \BackendBundle\Entity\Business $business
     */
    public function removeBusiness(Business $business)
    {
        if ($this->businesses->contains($business)) {
            $this->businesses->add($business);
        }
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
     * @return ArrayCollection
     */
    public function getBusinesses()
    {
        return $this->businesses;
    }

    /**
     * @param ArrayCollection $businesses
     */
    public function setBusinesses($businesses)
    {
        $this->businesses = $businesses;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Category $parent
     */
    public function setParent(Category $parent)
    {
        $this->parent = $parent;
    }
}