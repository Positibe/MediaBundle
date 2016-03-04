<?php

namespace Positibe\Bundle\OrmMediaBundle\Entity;

use Positibe\Bundle\OrmMediaBundle\Model\GalleryHasMediaInterface;
use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\OrmMediaBundle\Model\GalleryInterface;
use Positibe\Bundle\OrmMediaBundle\Model\MediaInterface;

/**
 * Class GalleryHasMedia
 * @package Positibe\Bundle\OrmMediaBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * @ORM\Table(name="positibe_gallery_media")
 * @ORM\Entity
 */
class GalleryHasMedia implements GalleryHasMediaInterface
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
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\OrmMediaBundle\Entity\Media", cascade="all")
     */
    protected $media;

    /**
     * @var Gallery
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\OrmMediaBundle\Entity\Gallery", inversedBy="galleryHasMedias")
     */
    protected $gallery;

    protected $position;

    protected $updatedAt;

    protected $createdAt;

    protected $enabled;

    public function __construct()
    {
        $this->position = 0;
        $this->enabled = false;
    }

    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
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
        return $this->getGallery() . ' | ' . $this->getMedia();
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
}