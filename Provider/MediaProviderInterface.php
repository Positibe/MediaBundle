<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMediaBundle\Provider;

use Positibe\Bundle\OrmMediaBundle\Model\MediaInterface;


/**
 * Class MediaProviderInterface
 * @package Positibe\Bundle\OrmMediaBundle\Provider
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface MediaProviderInterface {
    /**
     *
     * @param MediaInterface $media
     *
     * @return void
     */
    public function preUpdate(MediaInterface $media);

    /**
     *
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function postUpdate(MediaInterface $media);

    /**
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function preRemove(MediaInterface $media);

    /**
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function postRemove(MediaInterface $media);

    /**
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
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
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     */
    public function transform(MediaInterface $media);
} 