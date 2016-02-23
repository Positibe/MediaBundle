<?php

namespace Positibe\Bundle\OrmMediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Sonata\MediaBundle\Entity\BaseGallery as BaseGallery;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Gallery
 * @package Positibe\Bundle\OrmMediaBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * @ORM\Table(name="positibe_media_gallery")
 * @ORM\Entity
 */
class Gallery extends BaseGallery
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
     * @ORM\OneToMany(targetEntity="Positibe\Bundle\OrmMediaBundle\Entity\GalleryHasMedia", mappedBy="gallery", cascade="all", orphanRemoval=TRUE)
     */
    protected $galleryHasMedias;

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
        parent::__construct();
        $this->name = uniqid('gallery');
        $this->context = 'sonata.media.provider.file';
        $this->enabled = true;
        $this->defaultFormat = 'txt';
    }


    /**
     * @param GalleryHasMedia $galleryHasMedia
     * @return $this
     */
    public function addGalleryHasMedia(GalleryHasMedia $galleryHasMedia)
    {
        $galleryHasMedia->setGallery($this);

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
}