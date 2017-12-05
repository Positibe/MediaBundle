<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Positibe\Bundle\MediaBundle\Model\MediaInterface;
use Positibe\Bundle\MediaBundle\Provider\MediaProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class MediaEntityListener
 * @package Positibe\Bundle\MediaBundle\EventListener
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaEntityListener implements EventSubscriber
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
            Events::postUpdate,
            Events::postPersist,
        ];
    }

    public function prePersist(MediaInterface $media, LifecycleEventArgs $args)
    {
        if (!($provider = $this->getProvider($media))) {
            return;
        }

        if($media->getPath()) {
            $provider->updateMediaFromPath($media, null);
        }

        $provider->transform($media);
        $provider->prePersist($media);
    }

    /**
     * @param MediaInterface $media
     * @param LifecycleEventArgs $args
     */
    public function postPersist(MediaInterface $media, LifecycleEventArgs $args)
    {
        if (!($provider = $this->getProvider($media))) {
            return;
        }

        $provider->postPersist($media);
    }

    /**
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(MediaInterface $media, LifecycleEventArgs $args)
    {
        if (!($provider = $this->getProvider($media))) {
            return;
        }

        $provider->postUpdate($media);
    }

    /**
     * @param \Positibe\Bundle\MediaBundle\Model\MediaInterface $media
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(MediaInterface $media, LifecycleEventArgs $args)
    {
        if (!($provider = $this->getProvider($media))) {
            return;
        }

        if(isset($args->getEntityChangeSet()['path']) && $media->getPath()) {
            $provider->updateMediaFromPath($media, $args->getEntityChangeSet()['path'][0]);
        }

        $provider->transform($media);
        $provider->preUpdate($media);

        $this->recomputeSingleEntityChangeSet($media, $args);
    }

    /**
     * @param MediaInterface $media
     * @param LifecycleEventArgs $args
     */
    public function preRemove(MediaInterface $media, LifecycleEventArgs $args)
    {
        if (!($provider = $this->getProvider($media))) {
            return;
        }

        $provider->preRemove($media);
    }

    /**
     * @param MediaInterface $media
     * @return null|MediaProviderInterface
     */
    protected function getProvider(MediaInterface $media)
    {
        return $this->container->get($media->getProviderName());
    }

    /**
     * @param MediaInterface $media
     * @param LifecycleEventArgs $args
     */
    protected function recomputeSingleEntityChangeSet(MediaInterface $media, LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();

        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($media)), $media);
    }
} 