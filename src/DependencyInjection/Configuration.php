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

        /** @psalm-suppress MixedMethodCall,PossiblyNullReference,UndefinedInterfaceMethod,ReservedWord */
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('prerenderer')
                    ->info('A service id for the prerenderer service to use. The default value is "setono_prerender.prerenderer.prerender"')
                    ->cannotBeEmpty()
                    ->defaultValue('setono_prerender.prerenderer.prerender')
                ->end()
                ->arrayNode('adapter')
                    ->info('Configures the prerenderer services in this bundle')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('prerender')
                            ->info('Configure the prerender.io open source service')
                            ->beforeNormalization()
                                ->ifString()
                                ->then(static function (string $value) {
                                    return ['url' => $value];
                                })
                            ->end()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('url')
                                    ->defaultValue('http://localhost:3000')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('rendertron')
                            ->info('Configure the rendertron service. Notice that Rendertron is not actively maintained by Google as of October 2022')
                            ->beforeNormalization()
                                ->ifString()
                                ->then(static function (string $value) {
                                    return ['url' => $value];
                                })
                            ->end()
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('url')
                                    ->defaultValue('http://localhost:3000')
        ;

        return $treeBuilder;
    }
}
