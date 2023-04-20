<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RemovePrerenderAttributeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onRequest', 2000],
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        // this query parameter indicates that the current request is a prerender request
        if (!$request->query->has('_is_prerender_request')) {
            return;
        }

        $request->attributes->set('is_prerender_request', true);
        $request->attributes->remove('prerender');
    }
}
