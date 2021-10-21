<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Tests\Prerenderer\Adapter;

use PHPUnit\Framework\TestCase;
use Setono\PrerenderBundle\Prerenderer\Adapter\Rendertron;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @covers \Setono\PrerenderBundle\Prerenderer\Adapter\Rendertron
 */
final class RendertronTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders(): void
    {
        $rendertron = new Rendertron(new RequestStack(), HttpClient::create(), 'https://render-tron.appspot.com');
        $html = $rendertron->renderUrl('https://www.google.com');

        self::assertStringContainsString('<title>Google</title>', $html);
    }
}
