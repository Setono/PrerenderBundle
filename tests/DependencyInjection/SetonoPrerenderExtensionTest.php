<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Setono\PrerenderBundle\DependencyInjection\SetonoPrerenderExtension;
use Setono\PrerenderBundle\Prerenderer\PrerendererInterface;

/**
 * @covers \Setono\PrerenderBundle\DependencyInjection\SetonoPrerenderExtension
 */
final class SetonoPrerenderExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new SetonoPrerenderExtension(),
        ];
    }

    /**
     * @test
     */
    public function it_registers_services(): void
    {
        $this->load();

        // event_subscriber.xml
        $this->assertContainerBuilderHasService('setono_prerender.event_subscriber.prerender_request');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('setono_prerender.event_subscriber.prerender_request', 'kernel.event_subscriber');

        $this->assertContainerBuilderHasService('setono_prerender.event_subscriber.remove_prerender_attribute');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('setono_prerender.event_subscriber.remove_prerender_attribute', 'kernel.event_subscriber');

        // prerenderer.xml
        $this->assertContainerBuilderHasService(PrerendererInterface::class);
        $this->assertContainerBuilderHasService('setono_prerender.prerenderer');
        $this->assertContainerBuilderHasService('setono_prerender.prerenderer.rendertron');
        $this->assertContainerBuilderHasAlias(PrerendererInterface::class, 'setono_prerender.prerenderer');
        $this->assertContainerBuilderHasAlias('setono_prerender.prerenderer', 'setono_prerender.prerenderer.rendertron');
    }
}
