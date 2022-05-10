<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Prerenderer;

use Psr\Http\Message\UriInterface;
use Symfony\Component\HttpFoundation\Request;

interface PrerendererInterface
{
    /**
     * Will render the given URL and return the HTML.
     *
     * NOTICE: To avoid an infinite loop where a route with the prerender attribute is requesting to prerender itself
     * add the _is_prerender_request query parameter to URLs sent to your Prerenderer Adapter. This is automatically
     * handled if you extend the AbstractPrerenderer class
     *
     * @param string|UriInterface $url
     */
    public function renderUrl($url): string;

    /**
     * Will render the URL of the current request and return the HTML
     */
    public function renderMainRequest(): string;

    /**
     * Will render the URL of the given request and return the HTML
     */
    public function renderRequest(Request $request): string;
}
