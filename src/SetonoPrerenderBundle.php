<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle;

use Setono\PrerenderBundle\DependencyInjection\Compiler\RegisterPrerendererPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SetonoPrerenderBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterPrerendererPass());
    }
}
