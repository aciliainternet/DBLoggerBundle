<?php

namespace Acilia\Bundle\DBLoggerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('acilia_db_logger');
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('pdo')
                    ->children()
                        ->scalarNode('user')->end()
                        ->scalarNode('password')->end()
                        ->scalarNode('url')->end()
                    ->end()
                ->end() // pdo
            ->end()
        ;

        return $treeBuilder;

        return $treeBuilder;
    }
}
