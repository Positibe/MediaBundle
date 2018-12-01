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
use Symfony\Component\Asset\Packages;

/**
 * Class MediaExtension
 * @package Positibe\Bundle\MediaBundle\Twig\Extension
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaExtension extends \Twig_Extension
{
    protected $mediaHelper;
    protected $packages;

    /**
     * MediaExtension constructor.
     * @param Packages $packages
     * @param MediaHelper $mediaHelper
     */
    public function __construct(Packages $packages, MediaHelper $mediaHelper)
    {
        $this->packages = $packages;
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
            new \Twig_SimpleFunction('display_image', [$this, 'displayImage'], ['is_safe' => ['html']]),
        );
    }

    /**
     * Display the path of the media property
     *
     * @param null $file
     * @param array $options
     * @return string
     */
    public function displayImage($file = null, array $options = [])
    {
        return $this->packages->getUrl($this->mediaHelper->getImagePath($file, $options));
    }

    public function getName()
    {
        return 'positibe_media';
    }
}