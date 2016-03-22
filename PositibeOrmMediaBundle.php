<?php

namespace Positibe\Bundle\OrmMediaBundle;

use Positibe\Bundle\OrmMediaBundle\DependencyInjection\Compiler\OrmMediaCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PositibeOrmMediaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        if (!class_exists('Doctrine\ORM\Version')
            || !class_exists('Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterMappingsPass')
            || !class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')
        ) {
            return;
        }
        $container->addCompilerPass(new OrmMediaCompilerPass());
    }
}
