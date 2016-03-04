<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMediaBundle\Form\DataTransformer;

use Positibe\Bundle\OrmMediaBundle\Model\MediaInterface;
use Symfony\Component\Form\DataTransformerInterface;


/**
 * Class ProviderDataTransformer
 * @package Positibe\Bundle\OrmMediaBundle\Form\DataTransformer
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ProviderDataTransformer implements DataTransformerInterface
{
    protected $options;
    protected $class;

    /**
     * @param $class
     * @param array $options
     */
    public function __construct($class, array $options = array())
    {
        $this->options = $options;
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if ($value === null) {
            return new $this->class;
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($media)
    {
        if (!$media instanceof MediaInterface) {
            return $media;
        }

        $binaryContent = $media->getBinaryContent();

        // no binary content and no media id
        if (empty($binaryContent) && $media->getId() === null) {
            if ($this->options['empty_on_new']) {
                return null;
            }

            return $media;
        }

        // no update, but the the media exists ...
        if (empty($binaryContent) && $media->getId() !== null) {
            return $media;
        }

        // create a new media to avoid erasing other media or not ...
        $newMedia = $this->options['new_on_update'] ? new $this->class : $media;

        $newMedia->setBinaryContent($binaryContent);

        $newMedia->setProviderName($media->getProviderName());
        if (!$newMedia->getProviderName() && $this->options['provider']) {
            $newMedia->setProviderName($this->options['provider']);
        }

        return $newMedia;
    }
} 