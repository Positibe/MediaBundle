<?php

namespace Positibe\Bundle\MediaBundle;

use Positibe\Bundle\MediaBundle\DependencyInjection\Compiler\MediaCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PositibeMediaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MediaCompilerPass());
    }

}
