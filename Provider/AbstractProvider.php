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

use Gaufrette\Filesystem;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Positibe\Bundle\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Class AbstractProvider
 * @package Positibe\Bundle\MediaBundle\Provider
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
abstract class AbstractProvider
{
    /** @var  CacheManager */
    protected $cacheManager;
    /** @var Filesystem */
    protected $filesystem;
    protected $mediaUrlPath;

    /**
     * MediaProvider constructor.
     * @param CacheManager $cacheManager
     * @param Filesystem $filesystem
     * @param $mediaUrlPath
     */
    public function __construct(CacheManager $cacheManager, Filesystem $filesystem, $mediaUrlPath)
    {
        $this->cacheManager = $cacheManager;
        $this->filesystem = $filesystem;
        $this->mediaUrlPath = $mediaUrlPath;
    }


    /**
     *
     * @param MediaInterface $media
     *
     * @return void
     */
    public function preUpdate(MediaInterface $media)
    {
        if ($media->getBinaryContent()) {
            $this->removeFile($media->getPath());
            $media->setPath($this->createPath($media));
        }

        if ($media->getBinaryContentPreview()) {
            $this->removeFile($media->getPreview());
            $media->setPreview($this->createPreviewPath($media));
        }
    }

    /**
     *
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function postUpdate(MediaInterface $media)
    {
        if ($media->getBinaryContent() !== null) {
            $this->setFileContents($media);;
        }

        if ($media->getBinaryContentPreview() !== null) {
            $this->setFilePreviewContents($media);
        }
    }

    /**
     * @param MediaInterface $media
     *
     * @return void
     */
    public function preRemove(MediaInterface $media)
    {
        //If there is a problem to use $this->createPath($media)
        $this->removeFile($media->getPath());
        $this->removeFile($media->getPreview());
    }

    public function removeFile($file)
    {
        $this->cacheManager->remove($file);

        //Remove only files inside of uploadable directory
        if ($file && $this->filesystem->has($file) && substr_count(
                $file,
                $this->mediaUrlPath
            )) {
            $this->filesystem->delete($file);
        }
    }

    /**
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function postRemove(MediaInterface $media)
    {
    }

    /**
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    public function prePersist(MediaInterface $media)
    {
        if ($media->getBinaryContent()) {
            $media->setPath($this->createPath($media));
        }

        if ($media->getBinaryContentPreview()) {
            $media->setPreview($this->createPreviewPath($media));
        }
    }

    /**
     *
     * @param MediaInterface $media
     *
     * @return void
     */
    public function postPersist(MediaInterface $media)
    {
        if ($media->getBinaryContent() !== null) {
            $this->setFileContents($media);;
        }

        if ($media->getBinaryContentPreview() !== null) {
            $this->setFilePreviewContents($media);;
        }
    }

    /**
     * @param MediaInterface $media
     * @param $pathToRemove
     *
     * @return void
     */
    public function updateMediaFromPath(MediaInterface $media, $pathToRemove)
    {
        $file = $this->filesystem->get($media->getPath());

        $parts = explode('/', $file->getName());
        $media->setName($parts[count($parts) - 1]);
        $media->setContentType($this->filesystem->mimeType($media->getPath()));
        $media->setSize($file->getSize());
        $media->setProviderReference($media->getName());
        $media->setProviderStatus(1);
        $media->setMetadataValue('filename', $media->getName());

        $this->removeFile($pathToRemove);
    }

    /**
     * @param MediaInterface $media
     */
    public function transform(MediaInterface $media)
    {
        if ($media->getBinaryContentPreview()) {
            $this->fixBinaryContentPreview($media);
        }

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

    protected function createPreviewPath(MediaInterface $media)
    {
        return sprintf(
            '%s/preview-%s.%s',
            $this->generatePath($media),
            $media->getProviderReference(),
            $media->getBinaryContentPreview()->guessExtension()
        );
    }

    /**
     * Set the file contents for an image
     *
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     * @param string $contents path to contents, defaults to MediaInterface BinaryContent
     *
     * @return void
     */
    protected function setFileContents(MediaInterface $media, $contents = null)
    {
        $file = $this->filesystem->get($this->createPath($media), true);

        if (!$contents) {
            $contents = $media->getBinaryContent()->getRealPath();
        }

        $file->setContent(file_get_contents($contents));
    }

    /**
     * Set the file contents for an image
     *
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     *
     * @return void
     */
    protected function setFilePreviewContents(MediaInterface $media)
    {
        $filePreview = $this->filesystem->get($this->createPreviewPath($media), true);

        $contents = $media->getBinaryContentPreview()->getRealPath();

        $filePreview->setContent(file_get_contents($contents));
    }

    /**
     * {@inheritdoc}
     */
    public function generatePath(MediaInterface $media)
    {
        return $media->getUrlPathParameter() ?:
            sprintf('%s/%04s/%02s', $this->mediaUrlPath, date('Y'), date('W'));
    }

    /**
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     * @return string
     */
    protected function generateReferenceName(MediaInterface $media)
    {
        return sha1($media->getName().rand(11111, 99999)).'.'.$media->getBinaryContent()->guessExtension();
    }

    /**
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     */
    protected function fixBinaryContent(MediaInterface $media)
    {
        // if the binary content is a filename => convert to a valid File
        if (!$media->getBinaryContent() instanceof File) {
            if (!is_file($media->getBinaryContent())) {
                throw new \RuntimeException('The file does not exist : '.$media->getBinaryContent());
            }

            $binaryContent = new File($media->getBinaryContent());

            $media->setBinaryContent($binaryContent);
        }
    }

    protected function fixBinaryContentPreview(MediaInterface $media)
    {
        // if the binary content preview exist and is a filename => convert to a valid File
        if ($media->getBinaryContentPreview() && !($media->getBinaryContentPreview() instanceof File)) {
            if (!is_file($media->getBinaryContentPreview())) {
                throw new \RuntimeException('The file does not exist : '.$media->getBinaryContent());
            }

            $binaryContentPreview = new File($media->getBinaryContentPreview());

            $media->setBinaryContentPreview($binaryContentPreview);
        }
    }

    /**
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
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