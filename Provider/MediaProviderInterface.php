<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Provider;

use Positibe\Bundle\MediaBundle\Model\MediaInterface;


/**
 * Class MediaProviderInterface
 * @package Positibe\Bundle\MediaBundle\Provider
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface MediaProviderInterface
{
    const MEDIA_PROVIDER = 'positibe_media.media_provider';
    const IMAGE_PROVIDER = 'positibe_media.image_provider';

    /**
     * @return string
     */
    public static function getName();

    /**
     * @return string
     */
    public static function getFormTypeClass();

    /**
     * @param MediaInterface $media
     * @param $pathToRemove
     * @return mixed
     */
    public function updateMediaFromPath(MediaInterface $media, $pathToRemove);

    /**
     *
     * @param MediaInterface $media
     *
     * @return void
     */
    public function preUpdate(MediaInterface $media);

    /**
     *
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function postUpdate(MediaInterface $media);

    /**
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function preRemove(MediaInterface $media);

    /**
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function postRemove(MediaInterface $media);

    /**
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function prePersist(MediaInterface $media);

    /**
     *
     * @param MediaInterface $media
     *
     * @return void
     */
    public function postPersist(MediaInterface $media);

    /**
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     */
    public function transform(MediaInterface $media);
} 