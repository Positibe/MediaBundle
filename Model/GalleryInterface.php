<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Model;

use Pcabreus\Utils\Entity\TimestampableInterface;
use Pcabreus\Utils\Entity\ToggleableInterface;

/**
 * Interface GalleryInterface
 * @package Positibe\Bundle\MediaBundle\Model
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface GalleryInterface extends TimestampableInterface, ToggleableInterface
{
    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getContext();

    /**
     * @param string $context
     *
     * @return string
     */
    public function setContext($context);

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName();

    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled);

    /**
     * Get enabled
     *
     * @return boolean $enabled
     */
    public function getEnabled();

    /**
     * Set updated_at
     *
     * @param \Datetime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt = null);

    /**
     * Get updated_at
     *
     * @return \Datetime $updatedAt
     */
    public function getUpdatedAt();

    /**
     * Set created_at
     *
     * @param \Datetime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt = null);

    /**
     * Get created_at
     *
     * @return \Datetime $createdAt
     */
    public function getCreatedAt();

    /**
     * @param string $defaultFormat
     */
    public function setDefaultFormat($defaultFormat);

    /**
     * @return string
     */
    public function getDefaultFormat();

    /**
     * @param array $galleryHasMedias
     */
    public function setGalleryHasMedias($galleryHasMedias);

    /**
     * @return array
     */
    public function getGalleryHasMedias();

    /**
     * @param GalleryHasMediaInterface $galleryHasMedia
     */
    public function addGalleryHasMedia(GalleryHasMediaInterface $galleryHasMedia);

    /**
     * @return string
     */
    public function __toString();
} 