<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\EventSubscriber;

use Setono\BotDetectionBundle\BotDetector\BotDetectorInterface;
use Setono\PrerenderBundle\Prerenderer\PrerendererInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class PrerenderRequestSubscriber implements EventSubscriberInterface
{
    private PrerendererInterface $prerenderer;

    private BotDetectorInterface $botDetector;

    public function __construct(PrerendererInterface $prerenderer, BotDetectorInterface $botDetector)
    {
        $this->prerenderer = $prerenderer;
        $this->botDetector = $botDetector;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onRequest', 30],
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        $prerenderAttribute = $request->attributes->get('_prerender');
        if (null === $prerenderAttribute) {
            return;
        }

        if (!is_array($prerenderAttribute) && true !== $prerenderAttribute) {
            return;
        }

        if (is_array($prerenderAttribute)) {
            if (!isset($prerenderAttribute['bot']) || true !== $prerenderAttribute['bot']) {
                return;
            }

            if (!$this->botDetector->isBotRequest($request)) {
                return;
            }
        }

        $event->setResponse(new Response($this->prerenderer->renderRequest($request)));
        $event->stopPropagation();
    }
}
