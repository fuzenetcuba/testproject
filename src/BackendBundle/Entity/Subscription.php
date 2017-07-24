<?php

namespace BackendBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity(fields={"email"}, message="This email is already used.")
 * @ORM\Table(name="subscription")
 */
class Subscription
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $subscribed;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="subscriptions")
     * @Assert\NotBlank(message="You must have to choice one category at least.")
     */
    private $categories;

    public function __construct()
    {
        $this->date = new \DateTime("NOW");
        $this->subscribed = true;

        $this->categories = new ArrayCollection();
    }

    function __toString()
    {
        return $this->email;
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
     * Set email
     *
     * @param string $email
     * @return Subscription
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
     * Set date
     *
     * @param \DateTime $date
     * @return Subscription
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
     * Add categories
     *
     * @param Category $categories
     * @return Subscription
     */
    public function addCategory(Category $categories)
    {
        if (!$this->categories->contains($categories)) {
            $this->categories[] = $categories;
        }
        return $this;
    }

    /**
     * Remove categories
     *
     * @param Category $categories
     */
    public function removeCategory(Category $categories)
    {
        if (!$this->categories->contains($categories)) {
            $this->categories->removeElement($categories);
        }
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Get categories
     *
     * @param $categories
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setCategories($categories)
    {
        return $this->categories = $categories;
    }

    /**
     * Set subscribed
     *
     * @param boolean $subscribed
     * @return Subscription
     */
    public function setSubscribed($subscribed)
    {
        $this->subscribed = $subscribed;

        return $this;
    }

    /**
     * Get subscribed
     *
     * @return boolean 
     */
    public function getSubscribed()
    {
        return $this->subscribed;
    }
}
