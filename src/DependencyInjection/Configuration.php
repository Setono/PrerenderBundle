<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('setono_prerender');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('prerenderer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('rendertron')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('url')
                                    ->defaultValue('http://localhost:3000')
        ;

        return $treeBuilder;
    }
}
