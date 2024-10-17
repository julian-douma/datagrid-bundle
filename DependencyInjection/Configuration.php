<?php

namespace Skrepr\DatagridBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('abbert_datagrid');

        $dir = dirname(__FILE__);

        $rootNode
            ->children()
            ->arrayNode('view')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('path')->defaultValue($dir . '/../Resources/views/datagrid.phtml')->end()
            ->end()
            ->end() // view
            ->end();

        return $treeBuilder;
    }
}
