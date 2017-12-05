<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Templating\Helper;

use Liip\ImagineBundle\Templating\Helper\ImagineHelper;
use Positibe\Bundle\MediaBundle\Model\FileInterface;
use Positibe\Bundle\MediaBundle\Model\ImageInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\Helper\Helper;

/**
 * Class MediaHelper
 * @package Positibe\Bundle\MediaBundle\Templating\Helper
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaHelper extends Helper
{
    protected $generator;
    protected $imagineHelper;

    /**
     * Constructor.
     *
     * @param UrlGeneratorInterface $router A Router instance
     * @param ImagineHelper $imagineHelper Imagine helper to use if available
     */
    public function __construct(
        UrlGeneratorInterface $router,
        ImagineHelper $imagineHelper = null
    ) {
        $this->generator = $router;
        $this->imagineHelper = $imagineHelper;

    }

    /**
     * Generates a download URL from the given file.
     *
     * @param FileInterface $file
     * @param int $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     */
    public function downloadFile(FileInterface $file = null, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        if (!$file || !$urlSafePath = $file->getPath()) {
            return null;
        }

        return $this->generator->generate('positibe_media_download', array('path' => $urlSafePath), $referenceType);
    }

    /**
     * Generates a display URL from the given image.
     *
     * @param ImageInterface|string $file
     * @param array $options
     * @param int $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     */
    public function displayImage(
        $file = null,
        array $options = array(),
        $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ) {
        if (!$file || !$urlSafePath = $file instanceof ImageInterface ? $file->getPath() : $file) {
            return isset($options['default']) ? $options['default'] : '/bundles/positibemedia/images/photo.png';
        }

        if ($this->imagineHelper && isset($options['imagine_filter']) && is_string($options['imagine_filter'])) {
            return $this->imagineHelper->filter(
                $urlSafePath,
                $options['imagine_filter'],
                isset($options['imagine_runtime_config']) ? $options['imagine_runtime_config'] : array()
            );
        }

        return $this->generator->generate(
            'positibe_media_image_display',
            array('path' => $urlSafePath),
            $referenceType
        );
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return 'positibe_media';
    }
}