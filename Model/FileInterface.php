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
 * Class FileInterface
 * @package Positibe\Bundle\MediaBundle\Model
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface FileInterface extends MetadataInterface
{
    /**
     * @return string
     */
    public function getPath();

    public function setPath($path);

    /**
     * Returns the content.
     *
     * @return string
     */
    public function getContentAsString();

    /**
     * Set the content.
     *
     * @param string $content
     */
    public function setContentFromString($content);

    /**
     * Copy the content from a file, this allows to optimize copying the data
     * of a file. It is preferred to use the dedicated content setters if
     * possible.
     *
     * @param FileInterface|\SplFileInfo $file
     *
     * @throws \InvalidArgumentException if file is no FileInterface|\SplFileInfo
     */
    public function copyContentFromFile($file);

    /**
     * The mime type of this media element.
     *
     * @return string
     */
    public function getContentType();

    /**
     * Returns the extension of the file.
     *
     * @return string
     */
    public function getExtension();

    /**
     * Get the file size in bytes.
     *
     * @return int
     */
    public function getSize();
}