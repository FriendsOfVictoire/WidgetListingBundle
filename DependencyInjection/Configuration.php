<?php

namespace Victoire\Widget\ListingBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('victoire_listing');
        $rootNode
            ->children()
                ->scalarNode('name')->defaultValue('listing')->end()
                ->scalarNode('widgetName')->defaultValue('listing')->end()
                ->scalarNode('label')->defaultValue('widget.form.theme.listing')->end()
                ->scalarNode('entityClass')->defaultValue('Victoire\Widget\ListingBundle\Entity\WidgetListing')->end()
            ->end()
            // Here you should define the parameters that are allowed to configure your bundle.
        ;

        return $treeBuilder;
    }
}
