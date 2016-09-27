<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity("username")
 * @DoctrineAssert\UniqueEntity("email", message="Please use another email address, or log in with facebook.")
 * @Vich\Uploadable
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
     *      pattern="/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[a-z]).*$/",
     *      message="The password must contain at least 8 characters, one lower letter and one number or special character.",
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

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $points = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min="6",
     *     max="20",
     *     minMessage="The phone must have between 6 and 20 characters",
     *     maxMessage="The phone must have between 6 and 20 characters"
     * )
     * @Assert\Regex(
     *     pattern="/^(\(\+\d{1,3}\)|\+\d{1,3})?[\040\-]?\d?([\040\-]?\d{3,4}){2,4}$/",
     *     message="Enter a valid phone number please"
     * )
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Timestampable(on="create", on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="image"
     * )
     * @Assert\Image(
     *     minWidth="16",
     *     maxWidth="1500",
     *     minWidthMessage="The image must have a with between 16 and 1500 pixels",
     *     maxWidthMessage="The image must have a with between 16 and 1500 pixels",
     *     minHeight="16",
     *     maxHeight="1500",
     *     minHeightMessage="The image must have a height between 16 and 1500 pixels",
     *     maxHeightMessage="The image must have a height between 16 and 1500 pixels",
     *     maxSize="10M",
     *     maxSizeMessage="The image must have 10 MB (megabytes) or less"
     * )
     * @var File
     */
    private $imageFile;

    public function __construct()
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

    /**
     * @return mixed
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param mixed $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Returns the full name of the current user
     *
     * @return string
     */
    public function fullName()
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    /**
     * Increment the number of reward points to the current user
     * by the specified amount
     *
     * @param int $points
     */
    public function incrementPoints($points)
    {
        $this->points += $points;
    }

    /**
     * Decrement the number of reward points to the current user
     * by the specified amount
     *
     * @param int $points
     */
    public function reducePoints($points)
    {
        $this->points -= $points;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\File $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
