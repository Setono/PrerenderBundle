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
         * @var array{prerenderer: array{rendertron: array{url: string}}} $config
         */
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);

        $container->setParameter('setono_prerender.prerenderer.rendertron.url', $config['prerenderer']['rendertron']['url']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
}
