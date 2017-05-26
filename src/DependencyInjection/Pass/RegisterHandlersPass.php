<?php

declare(strict_types=1);

namespace UselessSoft\QueriesBundle\DependencyInjection\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use UselessSoft\QueriesBundle\DeferredQueryBus;
use UselessSoft\QueriesBundle\DependencyInjection\QueriesExtension;

class RegisterHandlersPass implements CompilerPassInterface
{
    public const TAG_NAME = QueriesExtension::ALIAS . '.handler';

    public function process(ContainerBuilder $container) : void
    {
        $locator = $container->getDefinition('queries.locator');
        $bus = $container->getDefinition(DeferredQueryBus::class);

        $handlerNames = array_keys($container->findTaggedServiceIds(self::TAG_NAME));

        $locator->setArgument(
            0,
            array_combine(
                $handlerNames,
                array_map(function (string $handlerName) : Reference {
                    return new Reference($handlerName);
                }, $handlerNames)
            )
        );

        $bus->replaceArgument('$handlerNames', $handlerNames);
    }
}
