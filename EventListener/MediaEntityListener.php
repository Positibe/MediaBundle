<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMediaBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Positibe\Bundle\OrmMediaBundle\Model\MediaInterface;
use Positibe\Bundle\OrmMediaBundle\Provider\MediaProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class MediaEntityListener
 * @package Positibe\Bundle\OrmMediaBundle\EventListener
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaEntityListener
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
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
            Events::postUpdate,
            Events::postRemove,
            Events::postPersist,
        );
    }

    public function prePersist(MediaInterface $media, LifecycleEventArgs $args)
    {
        if (!($provider = $this->getProvider($media))) {
            return;
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
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
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
     * @param MediaInterface $media
     * @param LifecycleEventArgs $args
     */
    public function postRemove(MediaInterface $media, LifecycleEventArgs $args)
    {
    }

    /**
     * @param \Positibe\Bundle\OrmMediaBundle\Model\MediaInterface $media
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(MediaInterface $media, LifecycleEventArgs $args)
    {
        if (!($provider = $this->getProvider($media))) {
            return;
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

        $em->getUnitOfWork()->recomputeSingleEntityChangeSet(
            $em->getClassMetadata(get_class($media)),
            $media
        );
    }
} 