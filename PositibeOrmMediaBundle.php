<?php

namespace Positibe\Bundle\MediaBundle;

use Positibe\Bundle\MediaBundle\DependencyInjection\Compiler\MediaCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PositibeMediaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        if (!class_exists('Doctrine\ORM\Version')
            || !class_exists('Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterMappingsPass')
            || !class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')
        ) {
            return;
        }
        $container->addCompilerPass(new MediaCompilerPass());
    }
}
