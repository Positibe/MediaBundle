<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\Imagine;

use Liip\ImagineBundle\Binary\BinaryInterface;
use Liip\ImagineBundle\Exception\Imagine\Cache\Resolver\NotResolvableException;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Cache\Resolver\ResolverInterface;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;

/**
 * Class FilesystemResolver
 * @package Positibe\Bundle\MediaBundle\Imagine
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class FilesystemResolver implements ResolverInterface
{
    /** @var  CacheManager */
    protected $cacheManager;

    /** @var  DataManager */
    protected $dataManager;

    /** @var  FilterManager */
    protected $filterManager;

    /** @var string */
    protected $webRoot;

    /** @var string */
    protected $cachePrefix;

    /** @var string */
    protected $cacheRoot;

    public function __construct(
        CacheManager $cacheManager,
        DataManager $dataManager,
        FilterManager $filterManager,
        $webRootDir,
        $cachePrefix = 'media/cache'
    ) {
        $this->cacheManager = $cacheManager;
        $this->dataManager = $dataManager;
        $this->filterManager = $filterManager;

        $this->webRoot = rtrim(str_replace('//', '/', $webRootDir), '/');
        $this->cachePrefix = ltrim(str_replace('//', '/', $cachePrefix), '/');
        $this->cacheRoot = $this->webRoot.'/'.$this->cachePrefix;
    }

    /**
     * Checks whether the given path is stored within this Resolver.
     *
     * @param string $path
     * @param string $filter
     *
     * @return bool
     */
    public function isStored($path, $filter)
    {
        return $this->cacheManager->isStored($path, $filter);
    }

    /**
     * Resolves filtered path for rendering in the browser.
     *
     * @param string $path The path where the original file is expected to be
     * @param string $filter The name of the imagine filter in effect
     *
     * @throws NotResolvableException
     *
     * @return string The absolute URL of the cached image
     */
    public function resolve($path, $filter = null)
    {
        if (!$filter) {
            return $this->webRoot.'/'.$path;
        }
        if (!$this->cacheManager->isStored($path, $filter)) {
            $binary = $this->dataManager->find($filter, $path);

            $this->cacheManager->store(
                $this->filterManager->applyFilter($binary, $filter),
                $path,
                $filter
            );
        }

        return $this->cacheRoot.'/'.$filter.'/'.$path;
    }

    /**
     * Stores the content of the given binary.
     *
     * @param BinaryInterface $binary The image binary to store
     * @param string $path The path where the original file is expected to be
     * @param string $filter The name of the imagine filter in effect
     */
    public function store(BinaryInterface $binary, $path, $filter)
    {
        $this->cacheManager->store($binary, $path, $filter);
    }

    /**
     * @param string[] $paths The paths where the original files are expected to be
     * @param string[] $filters The imagine filters in effect
     */
    public function remove(array $paths, array $filters)
    {
        $this->cacheManager->remove($paths, $filters);
    }

}