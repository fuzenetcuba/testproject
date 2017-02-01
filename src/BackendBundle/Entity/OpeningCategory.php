<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class OpeningCategory
 *
 * @package \BackendBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="opening_category")
 */
class OpeningCategory
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
     * @Assert\Length(min="3", minMessage="The name must have 3 characters or more")
     */
    private $name;

    /**
     * @var string
     *
     * @Slug(fields={"name"})
     * @Gedmo\Translatable
     * @ORM\Column(type="string", unique=true, length=128)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Translatable
     */
    private $icon;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Opening", mappedBy="categories")
     */
    private $openings;

    /**
     * @ORM\OneToMany(targetEntity="OpeningCategory", mappedBy="parent", cascade={"persist"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="OpeningCategory", inversedBy="children")
     */
    private $parent;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    private $locale;

    public function __construct()
    {
        $this->openings = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }


    /**
     * Adds a bussiness to this category, for keeping the bidirectional
     * relation
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
     * Remove bussiness from this category.
     *
     * @param \BackendBundle\Entity\Opening $opening
     */
    public function removeOpening(Opening $opening)
    {
        if ($this->openings->contains($opening)) {
            $this->openings->add($opening);
        }
    }

    public function addChildren(OpeningCategory $category)
    {
        if (!$this->children->contains($category)) {
            $category->setParent($this);
            $this->children->add($category);
        }
    }

    public function removeChildren(OpeningCategory $category)
    {
        if ($this->children->contains($category)) {
            $this->children->removeElement($category);
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
    public function getOpenings()
    {
        return $this->openings;
    }

    /**
     * @param ArrayCollection $openings
     */
    public function setOpenings($openings)
    {
        $this->openings = $openings;
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
     * @return OpeningCategory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param OpeningCategory $parent
     */
    public function setParent(OpeningCategory $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @param $locale   string
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
