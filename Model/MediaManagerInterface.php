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

use Positibe\Bundle\MediaBundle\Entity\Media;

/**
 * Interface MediaManagerInterface
 * @package Positibe\Bundle\MediaBundle\Model
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface MediaManagerInterface
{
    /**
     * @param $path
     * @return null|object|\Positibe\Bundle\MediaBundle\Entity\Media
     */
    public function getMediaByPath($path);

    /**
     * @param Media $media
     * @return mixed
     */
    public function getFilename(Media $media);

    /**
     * @param FileInterface $media
     * @return mixed
     */
    public function getUrlSafePath(FileInterface $media);
}