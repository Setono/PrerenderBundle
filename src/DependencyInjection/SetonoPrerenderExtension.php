<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoPrerenderExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        /**
         * @psalm-suppress PossiblyNullArgument
         *
         * @var array{prerenderer: string, adapter: array{prerender: array{url: string}, rendertron: array{url: string}}} $config
         */
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);

        $container->setParameter('setono_prerender.prerenderer', $config['prerenderer']);
        $container->setParameter('setono_prerender.adapter.prerender.url', $config['adapter']['prerender']['url']);
        $container->setParameter('setono_prerender.adapter.rendertron.url', $config['adapter']['rendertron']['url']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        /** @psalm-suppress ReservedWord */
        $loader->load('services.xml');
    }
}
