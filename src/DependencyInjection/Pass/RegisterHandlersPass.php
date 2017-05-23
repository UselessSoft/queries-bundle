<?php

declare(strict_types=1);

namespace UselessSoft\QueriesBundle\DependencyInjection\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use UselessSoft\Queries\QueryHandlerChain;
use UselessSoft\QueriesBundle\DependencyInjection\QueriesExtension;

class RegisterHandlersPass implements CompilerPassInterface
{
    public const TAG_NAME = QueriesExtension::ALIAS . '.handler';

    public function process(ContainerBuilder $container) : void
    {
        $chain = $container->getDefinition(QueryHandlerChain::class);

        foreach ($container->findTaggedServiceIds(self::TAG_NAME) as $serviceId => $tags) {
            $chain->addMethodCall('addHandler', [new Reference($serviceId)]);
        }
    }
}
