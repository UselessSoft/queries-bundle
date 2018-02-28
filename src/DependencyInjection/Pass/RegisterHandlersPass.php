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
        $handlerNames = $this->getHandlerNames($container);

        $locator->setArgument(0, $this->getHandlerReferences($this->getHandlerReferences($handlerNames)));
        $bus->replaceArgument('$handlerNames', $handlerNames);
    }

    /**
     * @return string[]
     */
    private function getHandlerNames(ContainerBuilder $container) : array
    {
        return array_filter(
            array_keys($container->findTaggedServiceIds(self::TAG_NAME)),
            function (string $name) : bool {
                return $name !== DeferredQueryBus::class;
            }
        );
    }

    /**
     * @param string[] $handlerNames
     * @return Reference[]
     */
    private function getHandlerReferences(array $handlerNames) : array
    {
        return array_combine(
            $handlerNames,
            array_map(function (string $handlerName) : Reference {
                return new Reference($handlerName);
            }, $handlerNames)
        );
    }
}
