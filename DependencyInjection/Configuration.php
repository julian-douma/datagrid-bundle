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
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $tb = new TreeBuilder('skrepr_datagrid');
        $rootNode = $tb->getRootNode();
        $rootNode
            ->children()
            ->arrayNode('view')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('path')->defaultValue(dirname(__FILE__) . '/../Resources/views/datagrid.phtml')->end()
            ->end()
            ->end() // view
            ->end();

        return $tb;
    }
}
