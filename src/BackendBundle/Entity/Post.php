<?php

namespace BackendBundle\Entity;

use BackendBundle\Validator\Constraints\RouteConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Post class
 *
 * @ORM\Entity()
 * @ORM\Table(name="post")
 * @ORM\HasLifecycleCallbacks()
 * @DoctrineAssert\UniqueEntity(
 *     fields={"route"},
 *     errorPath="route",
 *     message="Already exist a Post with this route"
 * )
 */
class Post
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     * @Assert\Length(min="3", minMessage="The title must have 3 characters or more")
     * @Gedmo\Translatable
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=255)
     * @Assert\NotBlank(message="The route must have 3 characters or more")
     * @Assert\Regex(pattern="/^[a-z0-9]+(?:-[a-z0-9]+)*$/", message="The route must have only alphanumeric and hyphen characters and a slash at begin. Eg.: /custom-route")
     * @Gedmo\Translatable
     */
    private $route;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $content;

    /**
     * @var Business
     *
     * @ORM\ManyToOne(targetEntity="SystemUser")
     * @ORM\JoinColumn(name="system_user_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $published = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="string", length=255)
     * @Assert\Length(min="3", minMessage="The meta title must have 3 characters or more")
     * @Gedmo\Translatable
     */
    private $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keywords", type="string", length=500)
     * @Assert\Length(min="3", minMessage="The meta keywords must have 3 characters or more")
     * @Gedmo\Translatable
     */
    private $metaKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="optional_metas", type="text", nullable=true)
     */
    private $optionalMetas;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_css", type="text", nullable=true)
     */
    private $customCss;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_js", type="text", nullable=true)
     */
    private $customJs;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="PostImage", mappedBy="post")
     */
    private $images;

    public function __construct()
    {
        $this->createdAt = new \DateTime();

        $this->customCss = "/* EDITOR FOR CSS CODE
=======================================================================================
Write your separate custom CSS code here. No need to write the code inside STYLE tags
======================================================================================= */";
        $this->customJs = "<script type=\"text/javascript\">
/* EDITOR FOR JAVASCRIPT CODE
===================================================================================================
Write your separate custom Javascript code here. 
You must write the code into SCRIPT tags, or even you can include some importing files 
=================================================================================================== */
</script>";

        $this->optionalMetas = "<!-- EDITOR FOR HTML MIXED CODE 																		-->
<!-- ==================================================================================================	-->
<!-- You can add some meta tags to support another feature in this post.  								-->
<!-- Eg.: <meta property=\"og:title\" content=\"Plaza Mariachi Music City\" />								-->
<!-- ==================================================================================================	-->
<!-- As this code is insert into the <head></head> section, you can include also some  					-->
<!-- <link /> tag to import CSS files. 																	-->
<!-- Eg.: <link rel=\"stylesheet\" href=\"/bundles/backend/css/bootstrap.min.css\" type=\"text/css\"/>		-->
<!-- ==================================================================================================	-->";

        $this->content = "<!-- EDITOR FOR HTML MIXED CODE 																		-->
<!-- ==================================================================================================	-->
<!-- Write your HTML code here. You can mix some kind of code, like CSS and Javascript, just like a  	-->
<!-- real code editor.  																				-->
<!-- ==================================================================================================	-->
<!-- The content will be rendered inside tags prepared for that, it means you have no wrap the 			-->
<!-- code inside HTML and/or BODY tags.  																-->
<!-- ==================================================================================================	-->";

        if (null === $this->updatedAt) {
            $this->updatedAt = new \DateTime();
        }

        $this->images = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
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
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set route
     *
     * @param string $route
     * @return Post
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return Post
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     * @return Post
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     * @return Post
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return Post
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set optionalMetas
     *
     * @param string $optionalMetas
     * @return Post
     */
    public function setOptionalMetas($optionalMetas)
    {
        $this->optionalMetas = $optionalMetas;

        return $this;
    }

    /**
     * Get optionalMetas
     *
     * @return string
     */
    public function getOptionalMetas()
    {
        return $this->optionalMetas;
    }

    /**
     * Set customCss
     *
     * @param string $customCss
     * @return Post
     */
    public function setCustomCss($customCss)
    {
        $this->customCss = $customCss;

        return $this;
    }

    /**
     * Get customCss
     *
     * @return string
     */
    public function getCustomCss()
    {
        return $this->customCss;
    }

    /**
     * Set customJs
     *
     * @param string $customJs
     * @return Post
     */
    public function setCustomJs($customJs)
    {
        $this->customJs = $customJs;

        return $this;
    }

    /**
     * Get customJs
     *
     * @return string
     */
    public function getCustomJs()
    {
        return $this->customJs;
    }

    /**
     * Set author
     *
     * @param \BackendBundle\Entity\SystemUser $author
     * @return Post
     */
    public function setAuthor(\BackendBundle\Entity\SystemUser $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \BackendBundle\Entity\SystemUser
     */
    public function getAuthor()
    {
        return $this->author;
    }


    /**
     * Add images
     *
     * @param \BackendBundle\Entity\PostImage $images
     * @return Post
     */
    public function addImage(\BackendBundle\Entity\PostImage $images)
    {
        if ($this->images->contains($images)) {
            $this->images->add($images);
        }

        return $this;
    }

    /**
     * Remove images
     *
     * @param \BackendBundle\Entity\PostImage $images
     */
    public function removeImage(\BackendBundle\Entity\PostImage $images)
    {
        if ($this->images->contains($images)) {
            $this->images->removeElement($images);
        }
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set images
     *
     * @param ArrayCollection $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }
}
