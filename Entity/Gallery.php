<?php

namespace Positibe\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Sluggable\Util\Urlizer;
use Positibe\Bundle\MediaBundle\Model\GalleryHasMediaInterface;
use Positibe\Bundle\MediaBundle\Model\GalleryInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Gallery
 * @package Positibe\Bundle\MediaBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * @ORM\Table(name="positibe_gallery")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Gallery implements GalleryInterface
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
     * @var GalleryHasMedia[]|ArrayCollection
     *
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\OneToMany(targetEntity="Positibe\Bundle\MediaBundle\Entity\GalleryHasMedia", mappedBy="gallery", cascade={"persist", "remove"}, orphanRemoval=TRUE, fetch="EXTRA_LAZY")
     */
    protected $galleryHasMedias;

    /**
     * @var string $name
     */
    protected $context;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, unique=TRUE)
     */
    protected $name;

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

    protected $defaultFormat;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->galleryHasMedias = new ArrayCollection();
        $this->name = uniqid('gallery');
        $this->enabled = true;
        $this->defaultFormat = 'jpg';
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
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = Urlizer::urlize($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
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
    public function setDefaultFormat($defaultFormat)
    {
        $this->defaultFormat = $defaultFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultFormat()
    {
        return $this->defaultFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function setGalleryHasMedias($galleryHasMedias)
    {
        $this->galleryHasMedias = new ArrayCollection();

        foreach ($galleryHasMedias as $galleryHasMedia) {
            $this->addGalleryHasMedia($galleryHasMedia);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGalleryHasMedias()
    {
        return $this->galleryHasMedias;
    }

    /**
     * {@inheritdoc}
     */
    public function addMedia(Media $media, $title = null, $body = null)
    {
        $galleryHasMedia = new GalleryHasMedia();
        $galleryHasMedia->setTitle($title);
        $galleryHasMedia->setBody($body);
        $galleryHasMedia->setMedia($media);
        $galleryHasMedia->setGallery($this);
        $galleryHasMedia->getMedia()->setProviderMetadata(['gallery' <= $this->name]);

        $this->galleryHasMedias[] = $galleryHasMedia;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getName() ?: '-';
    }

    /**
     * @param string $context
     * @return string|void
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param GalleryHasMediaInterface $galleryHasMedia
     * @return $this
     */
    public function addGalleryHasMedia(GalleryHasMediaInterface $galleryHasMedia)
    {
        $galleryHasMedia->setGallery($this);
        $galleryHasMedia->getMedia()->addProviderMetadata('gallery', $this->name);

        $this->galleryHasMedias[] = $galleryHasMedia;

        return $this;
    }

    /**
     * @param GalleryHasMedia $galleryHasMedia
     * @return $this
     */
    public function removeGalleryHasMedia(GalleryHasMedia $galleryHasMedia)
    {
        $this->galleryHasMedias->removeElement($galleryHasMedia);

        return $this;
    }

    /**
     * @return array
     */
    public function getMedias()
    {
        $medias = [];
        foreach ($this->galleryHasMedias as $galleryHasMedia) {
            $media = $galleryHasMedia->getMedia();
            $media->setName($galleryHasMedia->getTitle() ?: $media->getName());
            $medias[] = $media;
        }

        return $medias;
    }
}