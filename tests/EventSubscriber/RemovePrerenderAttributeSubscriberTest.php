<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Tests\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\PrerenderBundle\EventSubscriber\RemovePrerenderAttributeSubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @covers \Setono\PrerenderBundle\EventSubscriber\RemovePrerenderAttributeSubscriber
 */
final class RemovePrerenderAttributeSubscriberTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_removes_attribute(): void
    {
        $request = new Request(['_is_prerender_request' => true], [], ['prerender' => true]);

        $event = new RequestEvent($this->prophesize(HttpKernelInterface::class)->reveal(), $request, 1);

        $subscriber = new RemovePrerenderAttributeSubscriber();
        $subscriber->onRequest($event);

        self::assertFalse($request->attributes->has('prerender'));
    }
}
