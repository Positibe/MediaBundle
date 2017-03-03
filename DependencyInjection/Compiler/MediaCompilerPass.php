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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


/**
 * Class MediaCompilerPass
 * @package Positibe\Bundle\MediaBundle\DependencyInjection\Compiler
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MediaCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (isset($container->getParameter('kernel.bundles')['CmfMediaBundle'])) {
            // adding the cmf editor to the own upload editor
            if ($tags = $container->findTaggedServiceIds('cmf_media.upload_editor_helper')) {
                if ($container->has('positibe_media.upload_file_helper')) {
                    $manager = $container->findDefinition('positibe_media.upload_file_helper');

                    foreach ($tags as $id => $tag) {
                        $manager->addMethodCall('addEditorHelper', array($tag[0]['alias'], new Reference($id)));
                    }
                }

                if ($container->has('positibe_media.upload_image_helper')) {
                    $manager = $container->findDefinition('positibe_media.upload_image_helper');

                    foreach ($tags as $id => $tag) {
                        $manager->addMethodCall('addEditorHelper', array($tag[0]['alias'], new Reference($id)));
                    }
                }
            }

            if ($tags = $container->findTaggedServiceIds('cmf_media.browser_file_helper')) {
                if ($container->has('positibe_media.browser_file_helper')) {
                    $manager = $container->findDefinition('positibe_media.browser_file_helper');

                    foreach ($tags as $id => $tag) {
                        $manager->addMethodCall(
                            'addEditorHelper',
                            array($tag[0]['editor'], $tag[0]['browser'], new Reference($id))
                        );
                    }
                }
            }
        }
    }
}