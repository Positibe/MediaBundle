<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Positibe\Bundle\MediaBundle\Model\MediaInterface;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ProviderDataTransformer
 * @package Positibe\Bundle\MediaBundle\Form\DataTransformer
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ProviderDataTransformer implements DataTransformerInterface
{
    protected $options;
    protected $mediaClass;
    protected $em;

    /**
     * ProviderDataTransformer constructor.
     * @param EntityManagerInterface $entityManager
     * @param $mediaClass
     * @param array $options
     */
    public function __construct(EntityManagerInterface $entityManager, $mediaClass, array $options = array())
    {
        $this->em = $entityManager;
        $this->options = $options;
        $this->mediaClass = $mediaClass;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if ($value === null) {
            return new $this->mediaClass;
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
        if ($media->getBinaryContentPreview()) {
            $media->setUpdatedAt(new \DateTime());
        }

        // no binary content and no media id
        if (empty($binaryContent) && $media->getId() === null) {
            if ($this->options['empty_on_new'] && empty($media->getPath())) {
                return null;
            }
            $media->setProviderName($this->options['provider']);

            return $media;
        }

        // no update, but the media exists ...
        if (empty($binaryContent) && $media->getId() !== null) {
            $media->setProviderName($this->options['provider']);

            return $media;
        }

        // create a new media to avoid erasing other media or not ...
        $newMedia = $this->options['new_on_update'] ? new $this->mediaClass : $media;

        $newMedia->setBinaryContent($binaryContent);
        $newMedia->setBinaryContentPreview($media->getBinaryContentPreview());

        $newMedia->setProviderName($media->getProviderName());
        if ($this->options['provider']) {
            $newMedia->setProviderName($this->options['provider']);
        }

        $this->em->remove($media);

        return $newMedia;
    }
} 