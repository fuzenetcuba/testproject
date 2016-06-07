<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity("username")
 * @DoctrineAssert\UniqueEntity("email")
 * @ORM\Table(name="system_user")
 */
class SystemUser extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $googleId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $googleAccessToken;

    /**
     * @Assert\NotBlank(message="fos_user.password.blank", groups={"Registration", "ResetPassword", "ChangePassword"})
     * @Assert\Length(min="5", max="4096", minMessage="fos_user.password.short", groups={"Registration", "Profile", "ResetPassword", "ChangePassword"})
     * @Assert\Regex(
     *      pattern="/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/",
     *      message="The password must contain at least 8 characters, one upper letter, one lower letter and one number or special character.",
     *      groups={"Registration", "ResetPassword", "ChangePassword"}
     * )
     */
    protected $plainPassword;

    /**
     * @var Business
     *
     * @ORM\ManyToMany(targetEntity="Business", inversedBy="customers")
     */
    private $business;

    /**
     * @var Business
     *
     * @ORM\ManyToMany(targetEntity="Reward", inversedBy="customers")
     */
    private $rewards;

    function __construct()
    {
        parent::__construct();

        $this->business = new ArrayCollection();
        $this->rewards = new ArrayCollection();
    }

    public function getExpiresAt()
    {
        $this->expiresAt;
    }

    public function getCredentialsExpireAt()
    {
        $this->credentialsExpireAt;
    }

    public function eraseCredentials()
    {

    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPasswordActual()
    {
        return $this->password;
    }

//    public function getRoles() {
//        return array($this->roles);
//    }
//
//    /**
//     * @param String
//     */
//    public function setRol($role) {
//        $this->roles = $role;
//    }
//
//    public function setRoles(array $roles) {
//        $this->roles = $roles[0];
//    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setPasswordActual($password)
    {
        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return SystemUser
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set facebookAccessToken
     *
     * @param string $facebookAccessToken
     *
     * @return SystemUser
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebookAccessToken
     *
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * Set googleId
     *
     * @param string $googleId
     *
     * @return SystemUser
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * Get googleId
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * Set googleAccessToken
     *
     * @param string $googleAccessToken
     *
     * @return SystemUser
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->googleAccessToken = $googleAccessToken;

        return $this;
    }

    /**
     * Get googleAccessToken
     *
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->googleAccessToken;
    }

    /**
     * Add business
     *
     * @param Business $business
     * @return SystemUser
     */
    public function addBusiness(Business $business)
    {
        if (!$this->business->contains($business)) {
            $this->business->add($business);
        }
        return $this;
    }

    /**
     * Remove business
     *
     * @param Business $business
     */
    public function removeBusiness(Business $business)
    {
        if ($this->business->contains($business)) {
            $this->business->removeElement($business);
        }
    }

    /**
     * Get business
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @param Business $business
     */
    public function setBusiness($business)
    {
        $this->business = $business;
    }

    /**
     * Add rewards
     *
     * @param Reward $rewards
     * @return SystemUser
     */
    public function addReward(Reward $rewards)
    {
        if (!$this->rewards->contains($rewards)) {
            $this->rewards->add($rewards);
        }
        return $this;
    }

    /**
     * Remove rewards
     *
     * @param Reward $rewards
     */
    public function removeReward(Reward $rewards)
    {
        if ($this->rewards->contains($rewards)) {
            $this->rewards->removeElement($rewards);
        }
    }

    /**
     * Get rewards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRewards()
    {
        return $this->rewards;
    }

    /**
     * @param Business $rewards
     */
    public function setRewards($rewards)
    {
        $this->rewards = $rewards;
    }
}
