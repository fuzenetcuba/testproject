<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * PressPost
 *
 * @ORM\Table(name="press_post")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\PressPostRepository")
 * @Vich\Uploadable
 */
class PressPost
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="imave", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    private $image;

    /**
     * @var File
     *
     * This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="press_image", fileNameProperty="image",
     *      groups={"creation"}
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
     *     maxSize="1M",
     *     maxSizeMessage="The image must have 1 MB (megabytes) or less"
     * )
     */
    private $imageFile;

    /**
     * @var string
     *
     * @Assert\Url()
     * @ORM\Column(name="video", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    private $video;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Gedmo\Translatable
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     * @Gedmo\Translatable
     */
    private $author;

    /**
     * @var string
     *
     * @Assert\Url()
     * @ORM\Column(name="url", type="text")
     * @Gedmo\Translatable
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=255)
     * @Gedmo\Translatable
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    private $locale;

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
     * Set imave
     *
     * @param string $image
     *
     * @return PressPost
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get imave
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set video
     *
     * @param string $video
     *
     * @return PressPost
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return PressPost
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File $imageFile
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
    }

    /**
     * Set the translatable locale to refresh the entity
     *
     * @param $locale   string  Locale code
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param mixed $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }
}
