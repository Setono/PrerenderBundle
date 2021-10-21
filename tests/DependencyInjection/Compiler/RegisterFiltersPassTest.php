<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Tests\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Setono\PrerenderBundle\DependencyInjection\Compiler\RegisterPrerendererPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Setono\PrerenderBundle\DependencyInjection\Compiler\RegisterPrerendererPass
 */
final class RegisterFiltersPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @test
     */
    public function it_registers_prerenderer(): void
    {
        // todo
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterPrerendererPass());
    }
}
