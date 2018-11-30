<?php

namespace Positibe\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Sluggable\Util\Urlizer;
use Pcabreus\Utils\Entity\TimestampTrait;
use Pcabreus\Utils\Entity\ToggleableTrait;
use Pcabreus\Utils\Entity\TranslationTrait;
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
 * @ORM\HasLifecycleCallbacks()
 */
class Gallery implements GalleryInterface
{
    use TimestampTrait;
    use TranslationTrait;
    use ToggleableTrait;

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