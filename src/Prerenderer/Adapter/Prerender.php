<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Prerenderer\Adapter;

use Setono\PrerenderBundle\Prerenderer\AbstractPrerenderer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * This is an adapter for the prerender.io open source version: https://github.com/prerender/prerender
 */
final class Prerender extends AbstractPrerenderer
{
    private HttpClientInterface $httpClient;

    private string $prerenderUrl;

    public function __construct(
        RequestStack $requestStack,
        HttpClientInterface $httpClient,
        string $prerenderUrl = 'http://localhost:3000'
    ) {
        parent::__construct($requestStack);

        $this->httpClient = $httpClient;
        $this->prerenderUrl = rtrim($prerenderUrl, '/');
    }

    protected function _renderUrl($url): string
    {
        return $this->httpClient
            ->request('GET', sprintf('%s/render?url=%s', $this->prerenderUrl, rawurlencode((string) $url)))
            ->getContent()
        ;
    }
}
