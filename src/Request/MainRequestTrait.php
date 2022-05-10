<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Webmozart\Assert\Assert;

trait MainRequestTrait
{
    public function getMainRequestFromRequestStack(RequestStack $requestStack): ?Request
    {
        $request = null;

        if (method_exists($requestStack, 'getMainRequest')) {
            /** @var Request|mixed|null $request */
            $request = $requestStack->getMainRequest();
        } elseif (method_exists($requestStack, 'getMasterRequest')) {
            /** @var Request|mixed|null $request */
            $request = $requestStack->getMasterRequest();
        }

        Assert::nullOrIsInstanceOf($request, Request::class);

        return $request;
    }

    public function isMainRequest(KernelEvent $event): bool
    {
        $res = null;

        if (method_exists($event, 'isMainRequest')) {
            /** @var bool|mixed $res */
            $res = $event->isMainRequest();
        } elseif (method_exists($event, 'isMasterRequest')) {
            /** @var bool|mixed $res */
            $res = $event->isMasterRequest();
        }

        Assert::boolean($res);

        return $res;
    }
}
