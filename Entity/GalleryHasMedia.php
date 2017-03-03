<?php

namespace Positibe\Bundle\MediaBundle\Entity;

use Positibe\Bundle\MediaBundle\Model\FileInterface;
use Positibe\Bundle\MediaBundle\Model\GalleryHasMediaInterface;
use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\MediaBundle\Model\GalleryInterface;
use Positibe\Bundle\MediaBundle\Model\ImageInterface;
use Positibe\Bundle\MediaBundle\Model\MediaInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class GalleryHasMedia
 * @package Positibe\Bundle\MediaBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * @ORM\Table(name="positibe_gallery_media")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class GalleryHasMedia implements GalleryHasMediaInterface, FileInterface, ImageInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=255, nullable=TRUE)
     */
    protected $title;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="body", type="text", nullable=TRUE)
     */
    protected $body;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\MediaBundle\Entity\Media", cascade="all")
     */
    protected $media;

    /**
     * @var Gallery
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\MediaBundle\Entity\Gallery", inversedBy="galleryHasMedias")
     */
    protected $gallery;

    /**
     * @var integer
     *
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;

    /**
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    protected $locale;

    public function __construct()
    {
        $this->position = 0;
        $this->enabled = false;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
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
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function setGallery(GalleryInterface $gallery = null)
    {
        $this->gallery = $gallery;
    }

    /**
     * {@inheritdoc}
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * {@inheritdoc}
     */
    public function setMedia(MediaInterface $media = null)
    {
        $this->media = $media;
    }

    /**
     * {@inheritdoc}
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->title ?: $this->media->getName();
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the content.
     *
     * @return string
     */
    public function getContentAsString()
    {
        return $this->media->getContentAsString();
    }

    /**
     * Set the content.
     *
     * @param string $content
     */
    public function setContentFromString($content)
    {
        $this->media->setContentFromString($content);
    }

    /**
     * Copy the content from a file, this allows to optimize copying the data
     * of a file. It is preferred to use the dedicated content setters if
     * possible.
     *
     * @param FileInterface|\SplFileInfo $file
     *
     * @throws \InvalidArgumentException if file is no FileInterface|\SplFileInfo
     */
    public function copyContentFromFile($file)
    {
        $this->media->copyContentFromFile($file);
    }

    /**
     * The mime type of this media element.
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->media->getContentType();
    }

    /**
     * Returns the extension of the file.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->media->getExtension();
    }

    /**
     * Get the file size in bytes.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->media->getSize();
    }

    /**
     * The name of this media, e.g. for managing media documents.
     *
     * For example an image file name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->title ?: $this->media->getName();
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->media->setName($name);

        return $this;
    }

    /**
     * The description to show to users, e.g. an image caption or some text
     * to put after the filename.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->media->getDescription();
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->media->setDescription($description);
    }

    /**
     * The copyright text, e.g. a license name.
     *
     * @return string
     */
    public function getCopyright()
    {
        return $this->media->getCopyright();
    }

    /**
     * @param string $copyright
     */
    public function setCopyright($copyright)
    {
        $this->media->setCopyright($copyright);
    }

    /**
     * The name of the author of the media represented by this object.
     *
     * @return string
     */
    public function getAuthorName()
    {
        return $this->media->getAuthorName();
    }

    /**
     * @param string $author
     */
    public function setAuthorName($author)
    {
        $this->media->setAuthorName($author);
    }

    /**
     * Get all metadata.
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->media->getMetadata();
    }

    /**
     * Set all metadata.
     *
     * @param array $metadata
     *
     * @return mixed
     */
    public function setMetadata(array $metadata)
    {
        $this->media->setMetadata($metadata);
    }

    /**
     * @param string $name
     * @param string $default to be used if $name is not set in the metadata
     *
     * @return string
     */
    public function getMetadataValue($name, $default = null)
    {
        return $this->media->getMetadataValue($name, $default);
    }

    /**
     * The metadata value.
     *
     * @param string $name
     * @param string $value
     */
    public function setMetadataValue($name, $value)
    {
        $this->media->setMetadataValue($name, $value);
    }

    /**
     * Remove a named data from the metadata.
     *
     * @param string $name
     */
    public function unsetMetadataValue($name)
    {
        $this->media->unsetMetadataValue($name);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->media->getPath();
    }

    public function setPath($path)
    {
        $this->media->setPath($path);
    }

    /**
     * Get image width in pixels.
     *
     * @return int
     */
    public function getWidth()
    {
        $this->media->getWidth();
    }

    /**
     * Get image height in pixels.
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->media->getHeight();
    }

}