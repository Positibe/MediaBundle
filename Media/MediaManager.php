<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMediaBundle\Media;


use Symfony\Cmf\Bundle\MediaBundle\MediaInterface;
use Symfony\Cmf\Bundle\MediaBundle\MediaManagerInterface;


/**
 * Class MediaManager
 * @package Positibe\Bundle\OrmMediaBundle\Media
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaManager implements MediaManagerInterface
{
    /**
     * Get path, like:
     * - /path/to/file/filename.ext
     * - /fileId
     *
     * It is similar to a filesystem path only always uses "/" to separate
     * parents, and therefore allows to get the parent from the path.
     *
     * @param MediaInterface|\Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     *
     * @return string
     */
    public function getPath(MediaInterface $media)
    {
        return $media->getPath();
    }

    /**
     * Get an url safe path
     *
     * @param MediaInterface|\Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     *
     * @return string
     */
    public function getUrlSafePath(MediaInterface $media)
    {
        return $media->getPath();
    }

    /**
     * Set defaults for a media object;
     * this is used fe. by Doctrine Phpcr to ensure a unique id and add the
     * parent object.
     *
     * @param MediaInterface $media
     * @param string $parentPath optionally add the parent path
     *
     * @return void
     *
     * @throws \RuntimeException if the defaults could not be set
     */
    public function setDefaults(MediaInterface $media, $parentPath = null)
    {
        // TODO: Implement setDefaults() method.
    }

    /**
     * Map the path to an id that can be used to lookup the file in the
     * Doctrine store.
     *
     * @param string $path
     * @param string $rootPath
     *
     * @return string
     *
     * @throws \OutOfBoundsException if the path is out of the root path where
     *                               the filesystem is located
     */
    public function mapPathToId($path, $rootPath = null)
    {
        return $path;
    }

    /**
     * Map the requested path (ie. subpath in the URL) to an id that can
     * be used to lookup the file in the Doctrine store.
     *
     * @param string $path
     * @param string $rootPath
     *
     * @return string
     *
     * @throws \OutOfBoundsException if the path is out of the root path where
     *                               the filesystem is located
     */
    public function mapUrlSafePathToId($path, $rootPath = null)
    {
        return $path;
    }

} 