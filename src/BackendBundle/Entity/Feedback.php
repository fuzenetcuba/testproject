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
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $satisfied;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $recommended;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public $whyNotRecommend;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $storesSatisfied;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public $additionalStores;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $foodSatisfied;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public $additionalFood;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    public $rate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public $phone;

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
}