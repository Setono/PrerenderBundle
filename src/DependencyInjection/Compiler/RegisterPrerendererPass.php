<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterPrerendererPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var string $id */
        foreach ($container->findTaggedServiceIds('setono_prerender.prerenderer') as $id => $tags) {
        }
    }
}
