<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Media;

use Positibe\Bundle\MediaBundle\Entity\Media;
use Symfony\Cmf\Bundle\MediaBundle\MediaManagerInterface as CmfMediaManagerInterface;

/**
 * Interface MediaManagerInterface
 * @package Positibe\Bundle\MediaBundle\Media
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface MediaManagerInterface extends CmfMediaManagerInterface
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
}