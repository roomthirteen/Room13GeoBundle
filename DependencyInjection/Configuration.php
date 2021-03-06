<?php

namespace Room13\GeoBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('room13_geo');

        $rootNode
            ->children()
                ->booleanNode('twig')->cannotBeEmpty()->defaultTrue()->end()
                ->booleanNode('form')->cannotBeEmpty()->defaultTrue()->end()
                ->booleanNode('lookup')->cannotBeEmpty()->defaultTrue()->end()
                ->booleanNode('solr')->cannotBeEmpty()->defaultFalse()->end()
                ->scalarNode('cache_dir')->cannotBeEmpty()->defaultValue('%kernel.root_dir%/cache/room13geo')->end()
            ->end()
        ;
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
