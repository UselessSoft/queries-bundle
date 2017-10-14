<?php

declare(strict_types=1);

namespace UselessSoft\QueriesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use UselessSoft\Queries\QueryHandlerInterface;
use UselessSoft\QueriesBundle\DependencyInjection\Pass\RegisterHandlersPass;

class QueriesExtension extends Extension
{
    public const ALIAS = 'queries';

    public function getAlias() : string
    {
        return self::ALIAS;
    }

    public function load(array $config, ContainerBuilder $container) : void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container->registerForAutoconfiguration(QueryHandlerInterface::class)
            ->addTag(RegisterHandlersPass::TAG_NAME);
    }
}
