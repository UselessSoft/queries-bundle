<?php

declare(strict_types=1);

namespace UselessSoft\QueriesBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use UselessSoft\QueriesBundle\DependencyInjection\Pass\RegisterHandlersPass;

class QueriesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterHandlersPass());
    }

}
