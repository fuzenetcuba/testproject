<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Reward
 *
 * @package \BackendBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="reward")
 */
class Reward
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
     * @Assert\Length(min="3", minMessage="The name must have 3 characters or more")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\Length(min="3", minMessage="The description must have 3 characters or more")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *     pattern="/^\d+$/",
     *     message="The points must be a valid positive number"
     * )
     */
    private $cost;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="SystemUser", mappedBy="rewards")
     */
    private $customers;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $event;

    /**
     * Reward constructor.
     */
    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param integer $type
     *
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function setType($type)
    {
        if (!RewardType::hasValue($type)) {
            throw new InvalidArgumentException(sprintf('Wrong type [%s] passed to class', $type));
        }

        $this->type = $type;
    }

    /**
     * @return integer
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param integer $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * Add customers
     *
     * @param SystemUser $customers
     * @return Reward
     */
    public function addCustomer(SystemUser $customers)
    {
        if (!$this->customers->contains($customers)) {
            $this->customers->add($customers);
        }
        return $this;
    }

    /**
     * Remove customers
     *
     * @param SystemUser $customers
     */
    public function removeCustomer(SystemUser $customers)
    {
        if ($this->customers->contains($customers)) {
            $this->customers->removeElement($customers);
        }
    }

    /**
     * Get customers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * @param ArrayCollection $customers
     */
    public function setCustomers($customers)
    {
        $this->customers = $customers;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string $event
     *
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function setEvent($event)
    {
        if (!RewardEvents::hasValue($event)) {
            throw new InvalidArgumentException(sprintf('Wrong type [%s] passed to class', $event));
        }

        $this->event = $event;
    }
}
