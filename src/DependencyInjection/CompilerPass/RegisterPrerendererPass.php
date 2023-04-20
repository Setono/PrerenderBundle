<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\DependencyInjection\CompilerPass;

use Setono\PrerenderBundle\Prerenderer\PrerendererInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webmozart\Assert\Assert;

final class RegisterPrerendererPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('setono_prerender.prerenderer')) {
            return;
        }

        $prerendererServiceId = $container->getParameter('setono_prerender.prerenderer');
        Assert::stringNotEmpty($prerendererServiceId);

        $prerenderers = array_keys($container->findTaggedServiceIds('setono_prerender.prerenderer'));

        if (!in_array($prerendererServiceId, $prerenderers, true)) {
            throw new \RuntimeException(sprintf(
                'The prerenderer with service id "%s" does not exist or is not tagged with "setono_prerender.prerenderer"',
                $prerendererServiceId
            ));
        }

        $container->setAlias(PrerendererInterface::class, $prerendererServiceId);
        $container->setAlias('setono_prerender.prerenderer', $prerendererServiceId);
    }
}
