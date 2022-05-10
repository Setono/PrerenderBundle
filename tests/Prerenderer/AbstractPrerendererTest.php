<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Tests\Prerenderer;

use PHPUnit\Framework\TestCase;
use Setono\PrerenderBundle\Prerenderer\AbstractPrerenderer;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @covers \Setono\PrerenderBundle\Prerenderer\AbstractPrerenderer
 */
final class AbstractPrerendererTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders_url(): void
    {
        $requestStack = new RequestStack();

        $prerenderer = new Prerenderer($requestStack);
        $prerenderer->renderUrl('https://example.com');

        self::assertSame('https://example.com?_is_prerender_request', $prerenderer->renderedUrl);
    }
}

final class Prerenderer extends AbstractPrerenderer
{
    public ?string $renderedUrl = null;

    protected function _renderUrl($url): string
    {
        $this->renderedUrl = (string) $url;

        return 'response';
    }
}
