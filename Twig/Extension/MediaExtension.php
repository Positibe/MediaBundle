<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Twig\Extension;

use Positibe\Bundle\MediaBundle\Templating\Helper\MediaHelper;

/**
 * Class MediaExtension
 * @package Positibe\Bundle\MediaBundle\Twig\Extension
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaExtension extends \Twig_Extension
{
    protected $mediaHelper;

    /**
     * @param MediaHelper $mediaHelper
     */
    public function __construct(MediaHelper $mediaHelper)
    {
        $this->mediaHelper = $mediaHelper;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('download_file', [$this->mediaHelper, 'downloadFile'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('display_image', [$this->mediaHelper, 'displayImage'], ['is_safe' => ['html']]),
        );
    }

    public function getName()
    {
        return 'positibe_media';
    }
}