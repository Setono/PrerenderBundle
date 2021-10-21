<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Prerenderer;

use Symfony\Component\HttpFoundation\Request;

interface PrerendererInterface
{
    /**
     * Will render the given URL and return the HTML
     */
    public function renderUrl(string $url): string;

    /**
     * Will render the URL of the current request and return the HTML
     */
    public function renderMainRequest(): string;

    /**
     * Will render the URL of the given request and return the HTML
     */
    public function renderRequest(Request $request): string;
}
