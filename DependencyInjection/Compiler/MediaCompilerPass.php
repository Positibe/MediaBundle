<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MediaBundle\DependencyInjection\Compiler;

use Positibe\Bundle\MediaBundle\EventListener\MediaEntityListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


/**
 * Class MediaProvider
 * @package Positibe\Bundle\MediaBundle\DependencyInjection\Compiler
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has(MediaEntityListener::class)) {
            return;
        }

        $definition = $container->findDefinition(MediaEntityListener::class);

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds('positibe.media.provider');

        foreach ($taggedServices as $id => $tags) {
            // add the transport service to the ChainTransport service
            $definition->addMethodCall('addProvider', array(new Reference($id)));
        }
    }

}