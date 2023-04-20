<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Setono\PrerenderBundle\DependencyInjection\SetonoPrerenderExtension;

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

    protected function getMinimalConfiguration(): array
    {
        return [
            'prerenderer' => 'prerender',
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
        $this->assertContainerBuilderHasServiceDefinitionWithTag('setono_prerender.prerenderer.prerender', 'setono_prerender.prerenderer');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('setono_prerender.prerenderer.rendertron', 'setono_prerender.prerenderer');
    }

    /**
     * @test
     */
    public function it_casts_prerender_adapter_configuration_to_array(): void
    {
        $this->load([
            'adapter' => [
                'prerender' => 'http://my-url:3000',
            ],
        ]);

        $this->assertContainerBuilderHasParameter('setono_prerender.adapter.prerender.url', 'http://my-url:3000');
    }
}
