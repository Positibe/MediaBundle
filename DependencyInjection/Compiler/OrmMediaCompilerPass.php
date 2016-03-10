<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMediaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


/**
 * Class OrmMediaCompilerPass
 * @package Positibe\Bundle\OrmMediaBundle\DependencyInjection\Compiler
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class OrmMediaCompilerPass implements CompilerPassInterface
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
        // doctrine entity listener
        $container->getDefinition('doctrine.orm.default_entity_listener_resolver')->addMethodCall(
            'register',
            ['entityListener' => $container->getDefinition('positibe_orm_media.media_entity_listener')]
        );

        // added the editor to own upload editor
        $tags = $container->findTaggedServiceIds('cmf_media.upload_editor_helper');

        if (count($tags) > 0) {
            if ($container->has('positibe_orm_media.upload_file_helper')) {
                $manager = $container->findDefinition('positibe_orm_media.upload_file_helper');

                foreach ($tags as $id => $tag) {
                    $manager->addMethodCall('addEditorHelper', array($tag[0]['alias'], new Reference($id)));
                }
            }

            if ($container->has('positibe_orm_media.upload_image_helper')) {
                $manager = $container->findDefinition('positibe_orm_media.upload_image_helper');

                foreach ($tags as $id => $tag) {
                    $manager->addMethodCall('addEditorHelper', array($tag[0]['alias'], new Reference($id)));
                }
            }
        }

        $tags = $container->findTaggedServiceIds('cmf_media.browser_file_helper');

        if (count($tags) > 0) {
            if ($container->has('positibe_orm_media.browser_file_helper')) {
                $manager = $container->findDefinition('positibe_orm_media.browser_file_helper');

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