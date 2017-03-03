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

/**
 * Class ImageInterface
 * @package Positibe\Bundle\MediaBundle\Model
 *
 * Interface for image container objects. This just adds methods to get the
 * native image dimensions, but implicitly also tells applications that this
 * object is suitable to view with an <img> HTML tag.
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface ImageInterface extends FileInterface
{
    /**
     * Get image width in pixels.
     *
     * @return int
     */
    public function getWidth();

    /**
     * Get image height in pixels.
     *
     * @return int
     */
    public function getHeight();
}
