<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Prerenderer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractPrerenderer implements PrerendererInterface
{
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    protected function getMainRequest(): Request
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            throw new \RuntimeException('You are not calling this method inside a request/response lifecycle');
        }

        return $request;
    }
}
