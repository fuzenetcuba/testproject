<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Class Category
 *
 * @package \BackendBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @DoctrineAssert\UniqueEntity(
 *     fields={"name"},
 *     errorPath="name",
 *     message="Already exist a Category with this name"
 * )
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
     * @ORM\ManyToMany(targetEntity="Business", mappedBy="categories")
     */
    private $businesses;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", cascade={"persist"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     */
    private $parent;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Subscription", mappedBy="categories")
     */
    private $subscriptions;

    public function __construct()
    {
        $this->businesses = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
    }

    public function __toString()
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

    public function addChildren(Category $category)
    {
        if (!$this->children->contains($category)) {
            $category->setParent($this);
            $this->children->add($category);
        }
    }

    public function removeChildren(Category $category)
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
     * Add subscriptions
     *
     * @param Subscription $subscriptions
     * @return Category
     */
    public function addSubscription(Subscription $subscriptions)
    {
        if (!$this->subscriptions->contains($subscriptions)) {
            $this->subscriptions[] = $subscriptions;
        }

        return $this;
    }

    /**
     * Remove subscriptions
     *
     * @param Subscription $subscriptions
     */
    public function removeSubscription(Subscription $subscriptions)
    {
        if ($this->subscriptions->contains($subscriptions)) {
            $this->subscriptions->removeElement($subscriptions);
        }
    }

    /**
     * Get subscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param $locale   string
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
