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

use Doctrine\ORM\EntityManager;
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
    protected $class;
    protected $em;

    /**
     * @param EntityManager $entityManager
     * @param $class
     * @param array $options
     */
    public function __construct(EntityManager $entityManager, $class, array $options = array())
    {
        $this->em = $entityManager;
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
        $newMedia = $this->options['new_on_update'] ? new $this->class : $media;

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