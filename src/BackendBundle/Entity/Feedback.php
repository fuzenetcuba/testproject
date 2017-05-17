<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Feedback
 *
 * @package \BackendBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="feedback")
 */
class Feedback
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $satisfied;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $recommended;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $whyNotRecommend;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $storesSatisfied;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $additionalStores;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $foodSatisfied;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $additionalFood;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * summary
     */
    public function __construct()
    {
        $this->rate = 0;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set satisfied
     *
     * @param boolean $satisfied
     * @return Feedback
     */
    public function setSatisfied($satisfied)
    {
        $this->satisfied = $satisfied;

        return $this;
    }

    /**
     * Get satisfied
     *
     * @return boolean 
     */
    public function getSatisfied()
    {
        return $this->satisfied;
    }

    /**
     * Set recommended
     *
     * @param boolean $recommended
     * @return Feedback
     */
    public function setRecommended($recommended)
    {
        $this->recommended = $recommended;

        return $this;
    }

    /**
     * Get recommended
     *
     * @return boolean 
     */
    public function getRecommended()
    {
        return $this->recommended;
    }

    /**
     * Set whyNotRecommend
     *
     * @param string $whyNotRecommend
     * @return Feedback
     */
    public function setWhyNotRecommend($whyNotRecommend)
    {
        $this->whyNotRecommend = $whyNotRecommend;

        return $this;
    }

    /**
     * Get whyNotRecommend
     *
     * @return string 
     */
    public function getWhyNotRecommend()
    {
        return $this->whyNotRecommend;
    }

    /**
     * Set storesSatisfied
     *
     * @param boolean $storesSatisfied
     * @return Feedback
     */
    public function setStoresSatisfied($storesSatisfied)
    {
        $this->storesSatisfied = $storesSatisfied;

        return $this;
    }

    /**
     * Get storesSatisfied
     *
     * @return boolean 
     */
    public function getStoresSatisfied()
    {
        return $this->storesSatisfied;
    }

    /**
     * Set additionalStores
     *
     * @param string $additionalStores
     * @return Feedback
     */
    public function setAdditionalStores($additionalStores)
    {
        $this->additionalStores = $additionalStores;

        return $this;
    }

    /**
     * Get additionalStores
     *
     * @return string 
     */
    public function getAdditionalStores()
    {
        return $this->additionalStores;
    }

    /**
     * Set foodSatisfied
     *
     * @param boolean $foodSatisfied
     * @return Feedback
     */
    public function setFoodSatisfied($foodSatisfied)
    {
        $this->foodSatisfied = $foodSatisfied;

        return $this;
    }

    /**
     * Get foodSatisfied
     *
     * @return boolean 
     */
    public function getFoodSatisfied()
    {
        return $this->foodSatisfied;
    }

    /**
     * Set additionalFood
     *
     * @param string $additionalFood
     * @return Feedback
     */
    public function setAdditionalFood($additionalFood)
    {
        $this->additionalFood = $additionalFood;

        return $this;
    }

    /**
     * Get additionalFood
     *
     * @return string 
     */
    public function getAdditionalFood()
    {
        return $this->additionalFood;
    }

    /**
     * Set rate
     *
     * @param integer $rate
     * @return Feedback
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return integer 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Feedback
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
     * Set email
     *
     * @param string $email
     * @return Feedback
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
     * Set phone
     *
     * @param string $phone
     * @return Feedback
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
}
