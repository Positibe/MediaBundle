<?php

namespace Positibe\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\MediaBundle\Model\MediaInterface;
use Positibe\Bundle\MediaBundle\Provider\MediaProviderInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Media
 * @package Positibe\Bundle\MediaBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * @ORM\Table(name="positibe_media")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\MediaBundle\Repository\MediaRepository")
 * @ORM\EntityListeners({"Positibe\Bundle\MediaBundle\EventListener\MediaEntityListener"})
 */
class Media implements MediaInterface
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string $path
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=TRUE)
     */
    protected $path;

    /**
     * @var string $path
     *
     * @ORM\Column(name="preview_path", type="string", length=255, nullable=TRUE)
     */
    protected $preview;

    /**
     * this property is used to define the url_path to use to store the file from web/
     * e.j. media/uploads/videos or media/videos/sitio.cu
     */
    protected $urlPathParameter;

    protected $binaryContent;
    protected $binaryContentPreview;
    /**
     * @var string $description
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text", nullable=TRUE)
     */
    protected $description;

    /**
     * @var string $copyright
     *
     * @ORM\Column(name="copyright", type="string", length=255, nullable=TRUE)
     */
    protected $copyright;

    /**
     * @var string $authorName
     *
     * @ORM\Column(name="author_name", type="string", length=255, nullable=TRUE)
     */
    protected $authorName;

    /**
     * @var array $metadata
     *
     * @ORM\Column(name="metadata", type="array")
     */
    protected $metadata;

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
     * @ORM\Column(name="provider_name", type="string", length=255, nullable=TRUE)
     */
    protected $providerName = MediaProviderInterface::MEDIA_PROVIDER;

    /**
     * @var integer $provider_status
     *
     * @ORM\Column(name="provider_status", type="integer")
     */
    protected $providerStatus;

    /**
     * @var string $provider_reference
     *
     * @ORM\Column(name="provider_reference", type="string", length=255)
     */
    protected $providerReference;

    /**
     * @var array $provider_metadata
     *
     * @ORM\Column(name="provider_metadata", type="array")
     */
    protected $providerMetadata = array();

    /**
     * @var string $content_type
     *
     * @ORM\Column(name="content_type", type="string", length=255)
     */
    protected $contentType;

    /**
     * @var integer $size
     * @ORM\Column(name="size", type="integer")
     */
    protected $size;

    /**
     * @var integer $width
     *
     * @ORM\Column(name="width", type="integer", nullable=TRUE)
     */
    protected $width;

    /**
     * @var integer $height
     *
     * @ORM\Column(name="height", type="integer", nullable=TRUE)
     */
    protected $height;

    /**
     * @var GalleryHasMedia[]|ArrayCollection
     *
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\OneToMany(targetEntity="Positibe\Bundle\MediaBundle\Entity\GalleryHasMedia", mappedBy="gallery", cascade={"persist", "remove"}, orphanRemoval=TRUE, fetch="EXTRA_LAZY")
     */
    protected $galleryHasMedias;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    protected $locale;

    public function __construct()
    {
        $this->galleryHasMedias = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function isVideoType()
    {
        if (in_array($this->contentType, ['video/webm', 'video/mp4', 'video/ogg'])) {
            return true;
        }

        return false;
    }

    public function isImageType()
    {
        if (in_array($this->contentType, ['image/gif', 'image/jpeg', 'image/png'])) {
            return true;
        }

        return false;
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
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Returns the content
     *
     * @return string
     */
    public function getContentAsString()
    {
        return $this->path;
    }

    /**
     * Set the content
     *
     * @param string $content
     */
    public function setContentFromString($content)
    {
        $this->binaryContent = $content;
    }

    /**
     * Copy the content from a file, this allows to optimize copying the data
     * of a file. It is preferred to use the dedicated content setters if
     * possible.
     *
     * @param \SplFileInfo $file
     *
     * @throws \InvalidArgumentException if file is no FileInterface|\SplFileInfo
     */
    public function copyContentFromFile($file)
    {
        $this->binaryContent = $file;
    }

    /**
     * Returns the extension of the file
     *
     * @return string
     */
    public function getExtension()
    {
        return strtolower(str_replace('.', '', strrchr($this->name, '.')));
    }

    /**
     * @return mixed
     */
    public function getBinaryContent()
    {
        return $this->binaryContent;
    }

    /**
     * @param mixed $binaryContent
     */
    public function setBinaryContent($binaryContent)
    {
        $this->binaryContent = $binaryContent;
    }

    /**
     * @return string
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * @param string $providerName
     */
    public function setProviderName($providerName)
    {
        $this->providerName = $providerName;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function setProviderStatus($providerStatus)
    {
        $this->providerStatus = $providerStatus;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderStatus()
    {
        return $this->providerStatus;
    }

    /**
     * {@inheritdoc}
     */
    public function setProviderReference($providerReference)
    {
        $this->providerReference = $providerReference;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderReference()
    {
        return $this->providerReference;
    }

    /**
     * {@inheritdoc}
     */
    public function setProviderMetadata(array $providerMetadata = array())
    {
        $this->providerMetadata = $providerMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderMetadata()
    {
        return $this->providerMetadata;
    }

    /**
     * @param $name
     * @param $value
     */
    public function addProviderMetadata($name, $value)
    {
        $this->providerMetadata[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * {@inheritdoc}
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param $createdAt
     * @return mixed|void
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param $updatedAt
     * @return mixed|void
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
    }

    /**
     * {@inheritdoc}
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * {@inheritdoc}
     */
    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataValue($name, $default = null)
    {
        return isset($this->metadata[$name]) ? $this->metadata[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function setMetadataValue($name, $value)
    {
        $this->metadata[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function unsetMetadataValue($name)
    {
        unset($this->metadata[$name]);
    }

    /**
     * @return string
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * @param string $preview
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;
    }

    /**
     * @return mixed
     */
    public function getBinaryContentPreview()
    {
        return $this->binaryContentPreview;
    }

    /**
     * @param $binaryContentPreview
     */
    public function setBinaryContentPreview($binaryContentPreview)
    {
        $this->binaryContentPreview = $binaryContentPreview;
    }

    /**
     * @return ArrayCollection|GalleryHasMedia[]
     */
    public function getGalleryHasMedias()
    {
        return $this->galleryHasMedias;
    }

    /**
     * @param ArrayCollection|GalleryHasMedia[] $galleryHasMedias
     */
    public function setGalleryHasMedias($galleryHasMedias)
    {
        $this->galleryHasMedias = $galleryHasMedias;
    }

    /**
     * @return mixed
     */
    public function getUrlPathParameter()
    {
        return $this->urlPathParameter;
    }

    /**
     * @param mixed $urlPathParameter
     */
    public function setUrlPathParameter($urlPathParameter)
    {
        $this->urlPathParameter = $urlPathParameter;
    }

}