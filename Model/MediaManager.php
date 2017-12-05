<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Model;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\MediaBundle\Entity\Media;

/**
 * Class MediaManager
 * @package Positibe\Bundle\MediaBundle\Model
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaManager implements MediaManagerInterface
{
    protected $manager;
    protected $filesystemPath;

    public function __construct(EntityManager $entityManager, $filesystemPath)
    {
        $this->manager = $entityManager;
        $this->filesystemPath = $filesystemPath;
    }

    /**
     * @param $id
     * @return null|object|Media
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function find($id)
    {
        return $this->manager->find('PositibeMediaBundle:Media', $id);
    }

    /**
     * Get an url safe path
     *
     * @param FileInterface $media
     *
     * @return string
     */
    public function getUrlSafePath(FileInterface $media)
    {
        return $media->getPath();
    }

    /**
     * @param $path
     * @return \Positibe\Bundle\MediaBundle\Entity\Media
     */
    public function getMediaByPath($path)
    {
        return $this->manager->getRepository('PositibeMediaBundle:Media')->findOneBy(array('path' => $path));
    }

    /**
     * @param $path
     * @return \Positibe\Bundle\MediaBundle\Entity\Media
     */
    public function getMediaByPreviewPath($path)
    {
        return $this->manager->getRepository('PositibeMediaBundle:Media')->findOneBy(array('preview' => $path));
    }

    /**
     * Get the filename location of a given media
     *
     * @param Media $media
     * @return string
     */
    public function getFilename(Media $media)
    {
        return $this->filesystemPath.$media->getPath();
    }

    /**
     * @return mixed
     */
    public function getFilesystemPath()
    {
        return $this->filesystemPath;
    }

    /**
     * @param mixed $filesystemPath
     */
    public function setFilesystemPath($filesystemPath)
    {
        $this->filesystemPath = $filesystemPath;
    }
} 