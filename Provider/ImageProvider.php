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

use Positibe\Bundle\MediaBundle\Form\Type\ImageType;
use Positibe\Bundle\MediaBundle\Model\MediaInterface;

/**
 * Class ImageProvider
 * @package Positibe\Bundle\MediaBundle\Provider
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ImageProvider extends MediaProvider
{
    public function transform(MediaInterface $media)
    {
        parent::transform($media);

        if (!is_object($media->getBinaryContent()) && !$media->getBinaryContent()) {
            return;
        }

        try {
            $image = $this->container->get('liip_imagine')->open($media->getBinaryContent()->getPathname());
        } catch (\RuntimeException $e) {
            $media->setProviderStatus(MediaInterface::STATUS_ERROR);

            return;
        }

        $size = $image->getSize();

        $media->setWidth($size->getWidth());
        $media->setHeight($size->getHeight());

        $media->setProviderStatus(MediaInterface::STATUS_OK);
    }

    public static function getName()
    {
        return self::IMAGE_PROVIDER;
    }

    public static function getFormTypeClass()
    {
        return ImageType::class;
    }
} 