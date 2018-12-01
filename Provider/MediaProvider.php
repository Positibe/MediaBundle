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

use Positibe\Bundle\MediaBundle\Form\Type\MediaType;


/**
 * Class MediaProvider
 * @package Positibe\Bundle\MediaBundle\Provider
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaProvider extends AbstractProvider implements MediaProviderInterface
{
    public static function getName()
    {
        return self::MEDIA_PROVIDER;
    }

    public static function getFormTypeClass()
    {
        return MediaType::class;
    }
} 