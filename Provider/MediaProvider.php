<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMediaBundle\Provider;

use Gaufrette\Filesystem;
use Positibe\Bundle\OrmMediaBundle\Model\MediaInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Class MediaProvider
 * @package Positibe\Bundle\OrmMediaBundle\Provider
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaProvider implements ContainerAwareInterface, MediaProviderInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    public static function getName()
    {
        return 'positibe_orm_media.media_provider';
    }


    /**
     *
     * @param MediaInterface $media
     *
     * @return void
     */
    public function preUpdate(MediaInterface $media)
    {
        // TODO: Implement preUpdate() method.
    }

    /**
     *
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function postUpdate(MediaInterface $media)
    {
        // TODO: Implement postUpdate() method.
    }

    /**
     * @param MediaInterface $media
     *
     * @return void
     */
    public function preRemove(MediaInterface $media)
    {
        $path = $this->createPath($media);

        $this->container->get('liip_imagine.cache.manager')->remove($path);

        if ($this->getFilesystem()->has($path)) {
            $this->getFilesystem()->delete($path);
        }

    }

    /**
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function postRemove(MediaInterface $media)
    {
        // TODO: Implement postRemove() method.
    }

    /**
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function prePersist(MediaInterface $media)
    {
        $media->setCreatedAt(new \Datetime());
        $media->setUpdatedAt(new \Datetime());

        $media->setPath($this->createPath($media));
    }

    /**
     *
     * @param MediaInterface $media
     *
     * @return void
     */
    public function postPersist(MediaInterface $media)
    {
        if ($media->getBinaryContent() === null) {
            return;
        }

        $this->setFileContents($media);

//        $this->generateThumbnails($media);
    }

    /**
     * @param MediaInterface $media
     */
    public function transform(MediaInterface $media)
    {
        if (null === $media->getBinaryContent()) {
            return;
        }

        $this->fixBinaryContent($media);
        $this->fixFilename($media);

        // this is the name used to store the file
        if (!$media->getProviderReference()) {
            $media->setProviderReference($this->generateReferenceName($media));
        }

        if ($media->getBinaryContent()) {
            $media->setContentType($media->getBinaryContent()->getMimeType());
            $media->setSize($media->getBinaryContent()->getSize());
        }

        $media->setProviderStatus(MediaInterface::STATUS_OK);
    }

    protected function createPath(MediaInterface $media)
    {
        return sprintf('%s/%s', $this->generatePath($media), $media->getProviderReference());
    }

    /**
     * Set the file contents for an image
     *
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     * @param string $contents path to contents, defaults to MediaInterface BinaryContent
     *
     * @return void
     */
    protected function setFileContents(MediaInterface $media, $contents = null)
    {
        $file = $this->getFilesystem()->get($this->createPath($media), true);

        if (!$contents) {
            $contents = $media->getBinaryContent()->getRealPath();
        }

        $file->setContent(file_get_contents($contents));
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->container->get('positibe_orm_media.filesystem');
    }

    /**
     * {@inheritdoc}
     */
    public function generatePath(MediaInterface $media)
    {
        $rep_first_level = (int)($media->getId() / 100000);
        $rep_second_level = (int)(($media->getId() - ($rep_first_level * 100000)) / 1000);

        return sprintf('%s/%04s/%02s', $this->container->getParameter('positibe_orm_media.url_path'), $rep_first_level + 1, $rep_second_level + 1);
    }

    /**
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     * @return string
     */
    protected function generateReferenceName(MediaInterface $media)
    {
        return sha1($media->getName() . rand(11111, 99999)) . '.' . $media->getBinaryContent()->guessExtension();
    }

    /**
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     */
    protected function fixBinaryContent(MediaInterface $media)
    {
        // if the binary content is a filename => convert to a valid File
        if (!$media->getBinaryContent() instanceof File) {
            if (!is_file($media->getBinaryContent())) {
                throw new \RuntimeException('The file does not exist : ' . $media->getBinaryContent());
            }

            $binaryContent = new File($media->getBinaryContent());

            $media->setBinaryContent($binaryContent);
        }
    }

    /**
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     */
    protected function fixFilename(MediaInterface $media)
    {
        if ($media->getBinaryContent() instanceof UploadedFile) {
            $media->setName($media->getName() ?: $media->getBinaryContent()->getClientOriginalName());
            $media->setMetadataValue('filename', $media->getBinaryContent()->getClientOriginalName());
        } elseif ($media->getBinaryContent() instanceof File) {
            $media->setName($media->getName() ?: $media->getBinaryContent()->getBasename());
            $media->setMetadataValue('filename', $media->getBinaryContent()->getBasename());
        }

        // this is the original name
        if (!$media->getName()) {
            throw new \RuntimeException('Please define a valid media\'s name');
        }
    }
} 