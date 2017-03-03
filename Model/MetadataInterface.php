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
 * Class MetadataInterface
 * @package Positibe\Bundle\MediaBundle\Model
 *
 * A basic interface for media objects. Be they cloud hosted or local files.
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface MetadataInterface
{
    /**
     * Give the name
     *
     * @return mixed
     */
    public function getName();

    /**
     * @param $name
     * @return mixed
     */
    public function setName($name);

    /**
     * The description to show to users, e.g. an image caption or some text
     * to put after the filename.
     *
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     */
    public function setDescription($description);

    /**
     * The copyright text, e.g. a license name.
     *
     * @return string
     */
    public function getCopyright();

    /**
     * @param string $copyright
     */
    public function setCopyright($copyright);

    /**
     * The name of the author of the media represented by this object.
     *
     * @return string
     */
    public function getAuthorName();

    /**
     * @param string $author
     */
    public function setAuthorName($author);

    /**
     * Get all metadata.
     *
     * @return array
     */
    public function getMetadata();

    /**
     * Set all metadata.
     *
     * @param array $metadata
     *
     * @return mixed
     */
    public function setMetadata(array $metadata);

    /**
     * @param string $name
     * @param string $default to be used if $name is not set in the metadata
     *
     * @return string
     */
    public function getMetadataValue($name, $default = null);

    /**
     * The metadata value.
     *
     * @param string $name
     * @param string $value
     */
    public function setMetadataValue($name, $value);

    /**
     * Remove a named data from the metadata.
     *
     * @param string $name
     */
    public function unsetMetadataValue($name);
}
