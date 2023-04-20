<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Tests\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\BotDetectionBundle\BotDetector\BotDetectorInterface;
use Setono\PrerenderBundle\EventSubscriber\PrerenderRequestSubscriber;
use Setono\PrerenderBundle\EventSubscriber\RemovePrerenderAttributeSubscriber;
use Setono\PrerenderBundle\Prerenderer\PrerendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\EventListener\DebugHandlersListener;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @covers \Setono\PrerenderBundle\EventSubscriber\PrerenderRequestSubscriber
 */
final class PrerenderRequestSubscriberTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_sets_response_if_prerender_is_set_to_true(): void
    {
        $request = new Request([], [], ['prerender' => true]);

        $prerenderer = $this->prophesize(PrerendererInterface::class);
        $prerenderer->renderRequest($request)->willReturn('content');

        $event = new RequestEvent($this->prophesize(HttpKernelInterface::class)->reveal(), $request, 1);

        $subscriber = new PrerenderRequestSubscriber(
            $prerenderer->reveal(),
            $this->prophesize(BotDetectorInterface::class)->reveal()
        );
        $subscriber->onRequest($event);

        $response = $event->getResponse();
        self::assertNotNull($response);

        /** @psalm-suppress ReservedWord */
        self::assertSame('content', $response->getContent());
        self::assertTrue($event->isPropagationStopped());
    }

    /**
     * @test
     */
    public function it_sets_response_if_prerender_is_array_and_bot_is_true(): void
    {
        $request = new Request([], [], ['prerender' => ['bot' => true]]);

        $prerenderer = $this->prophesize(PrerendererInterface::class);
        $prerenderer->renderRequest($request)->willReturn('content');

        $botDetector = $this->prophesize(BotDetectorInterface::class);
        $botDetector->isBotRequest($request)->willReturn(true);

        $event = new RequestEvent($this->prophesize(HttpKernelInterface::class)->reveal(), $request, 1);

        $subscriber = new PrerenderRequestSubscriber(
            $prerenderer->reveal(),
            $botDetector->reveal()
        );
        $subscriber->onRequest($event);

        $response = $event->getResponse();
        self::assertNotNull($response);

        /** @psalm-suppress ReservedWord */
        self::assertSame('content', $response->getContent());
        self::assertTrue($event->isPropagationStopped());
    }

    /**
     * @test
     */
    public function it_does_not_do_anything_if_prerender_attribute_is_not_present(): void
    {
        $event = new RequestEvent($this->prophesize(HttpKernelInterface::class)->reveal(), new Request(), 1);

        $subscriber = new PrerenderRequestSubscriber(
            $this->prophesize(PrerendererInterface::class)->reveal(),
            $this->prophesize(BotDetectorInterface::class)->reveal()
        );
        $subscriber->onRequest($event);

        $response = $event->getResponse();
        self::assertNull($response);
        self::assertFalse($event->isPropagationStopped());
    }

    /**
     * @test
     */
    public function it_does_not_do_anything_if_prerender_attribute_is_false(): void
    {
        $request = new Request([], [], ['prerender' => false]);

        $event = new RequestEvent($this->prophesize(HttpKernelInterface::class)->reveal(), $request, 1);

        $subscriber = new PrerenderRequestSubscriber(
            $this->prophesize(PrerendererInterface::class)->reveal(),
            $this->prophesize(BotDetectorInterface::class)->reveal()
        );
        $subscriber->onRequest($event);

        $response = $event->getResponse();
        self::assertNull($response);
        self::assertFalse($event->isPropagationStopped());
    }

    /**
     * @test
     */
    public function it_does_not_do_anything_if_prerender_attribute_is_array_and_bot_is_false(): void
    {
        $request = new Request([], [], ['prerender' => ['bot' => false]]);

        $event = new RequestEvent($this->prophesize(HttpKernelInterface::class)->reveal(), $request, 1);

        $subscriber = new PrerenderRequestSubscriber(
            $this->prophesize(PrerendererInterface::class)->reveal(),
            $this->prophesize(BotDetectorInterface::class)->reveal()
        );
        $subscriber->onRequest($event);

        $response = $event->getResponse();
        self::assertNull($response);
        self::assertFalse($event->isPropagationStopped());
    }

    /**
     * @test
     */
    public function it_does_not_do_anything_if_request_is_not_a_bot(): void
    {
        $request = new Request([], [], ['prerender' => ['bot' => true]]);

        $event = new RequestEvent($this->prophesize(HttpKernelInterface::class)->reveal(), $request, 1);

        $botDetector = $this->prophesize(BotDetectorInterface::class);
        $botDetector->isBotRequest($request)->willReturn(false);

        $subscriber = new PrerenderRequestSubscriber(
            $this->prophesize(PrerendererInterface::class)->reveal(),
            $botDetector->reveal()
        );
        $subscriber->onRequest($event);

        $response = $event->getResponse();
        self::assertNull($response);
        self::assertFalse($event->isPropagationStopped());
    }

    /**
     * @test
     */
    public function it_has_correct_priorities(): void
    {
        // fetching and validating events for the event subscriber we are testing

        /** @var array<string, array{0: string, 1: int}> $ownEvents */
        $ownEvents = PrerenderRequestSubscriber::getSubscribedEvents();
        self::assertCount(1, $ownEvents);
        self::assertArrayHasKey(KernelEvents::REQUEST, $ownEvents);

        $ownPriority = $ownEvents[KernelEvents::REQUEST][1];
        self::assertIsInt($ownPriority);

        // fetching and validating events for the RemovePrerenderAttributeSubscriber
        /** @var array<string, array{0: string, 1: int}> $removePrerenderAttributeSubscriberEvents */
        $removePrerenderAttributeSubscriberEvents = RemovePrerenderAttributeSubscriber::getSubscribedEvents();
        self::assertCount(1, $removePrerenderAttributeSubscriberEvents);
        self::assertArrayHasKey(KernelEvents::REQUEST, $removePrerenderAttributeSubscriberEvents);

        $removePrerenderAttributeSubscriberPriority = $removePrerenderAttributeSubscriberEvents[KernelEvents::REQUEST][1];
        self::assertIsInt($removePrerenderAttributeSubscriberPriority);
        self::assertGreaterThan($ownPriority, $removePrerenderAttributeSubscriberPriority);

        // fetching and validating events for the DebugHandlersListener
        /**
         * @psalm-suppress InternalClass,InternalMethod
         *
         * @var array<string, array{0: string, 1: int}> $debugHandlersListenerEvents
         */
        $debugHandlersListenerEvents = DebugHandlersListener::getSubscribedEvents();
        self::assertArrayHasKey(KernelEvents::REQUEST, $debugHandlersListenerEvents);

        $debugHandlersListenerPriority = $debugHandlersListenerEvents[KernelEvents::REQUEST][1];
        self::assertIsInt($debugHandlersListenerPriority);
        self::assertGreaterThan($ownPriority, $debugHandlersListenerPriority);
    }
}
