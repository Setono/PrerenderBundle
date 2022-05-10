<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Prerenderer;

use League\Uri\Contracts\UriInterface;
use League\Uri\Uri;
use League\Uri\UriModifier;
use Psr\Http\Message\UriInterface as Psr7UriInterface;
use Setono\PrerenderBundle\Request\MainRequestTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractPrerenderer implements PrerendererInterface
{
    use MainRequestTrait;

    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function renderMainRequest(): string
    {
        return $this->renderRequest($this->getMainRequest());
    }

    public function renderRequest(Request $request): string
    {
        return $this->renderUrl($request->getUri());
    }

    public function renderUrl($url): string
    {
        return $this->_renderUrl(UriModifier::appendQuery(Uri::createFromString((string) $url), '_is_prerender_request'));
    }

    /**
     * @param string|Psr7UriInterface|UriInterface $url
     */
    abstract protected function _renderUrl($url): string;

    private function getMainRequest(): Request
    {
        $request = $this->getMainRequestFromRequestStack($this->requestStack);
        if (null === $request) {
            throw new \RuntimeException('You are not calling this method inside a request/response lifecycle');
        }

        return $request;
    }
}
