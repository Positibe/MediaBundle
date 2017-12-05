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

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface MediaInterface
 * @package Positibe\Bundle\MediaBundle\Model
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface MediaInterface extends MetadataInterface, ImageInterface
{
    const STATUS_OK = 1;
    const STATUS_SENDING = 2;
    const STATUS_PENDING = 3;
    const STATUS_ERROR = 4;
    const STATUS_ENCODING = 5;

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string|File|UploadedFile
     */
    public function getBinaryContent();

    public function setBinaryContent($binaryContent);

    /**
     * @return string
     */
    public function getProviderName();

    public function setProviderName($providerName);

    /**
     * Set provider_status
     *
     * @param integer $providerStatus
     */
    public function setProviderStatus($providerStatus);

    /**
     * Get provider_status
     *
     * @return integer $providerStatus
     */
    public function getProviderStatus();

    /**
     * Set provider_reference
     *
     * @param string $providerReference
     */
    public function setProviderReference($providerReference);

    /**
     * Get provider_reference
     *
     * @return string $providerReference
     */
    public function getProviderReference();

    /**
     * Set provider_metadata
     *
     * @param array $providerMetadata
     */
    public function setProviderMetadata(array $providerMetadata = array());

    /**
     * Get provider_metadata
     *
     * @return array $providerMetadata
     */
    public function getProviderMetadata();

    /**
     * @param $name
     * @param $value
     */
    public function addProviderMetadata($name, $value);

    /**
     * Set content_type
     *
     * @param string $contentType
     */
    public function setContentType($contentType);

    /**
     * Set size
     *
     * @param integer $size
     */
    public function setSize($size);

    /**
     * Get image width in pixels
     *
     * @param $width
     * @return mixed
     */
    public function setWidth($width);

    /**
     * Get image height in pixels
     *
     * @param $height
     * @return mixed
     */
    public function setHeight($height);

    /**
     * @param $createdAt
     * @return mixed
     */
    public function setCreatedAt($createdAt);

    /**
     * @param $createdAt
     * @return mixed
     */
    public function setUpdatedAt($createdAt);

    /**
     * @return mixed
     */
    public function getPreview();

    /**
     * @param $path
     * @return mixed
     */
    public function setPreview($path);

    /**
     * @return mixed
     */
    public function getBinaryContentPreview();

    /**
     * @param $binaryContentPreview
     * @return mixed
     */
    public function setBinaryContentPreview($binaryContentPreview);
    /**
     * @return mixed
     */
    public function getUrlPathParameter();

    /**
     * @param mixed $urlPathParameter
     */
    public function setUrlPathParameter($urlPathParameter);
}