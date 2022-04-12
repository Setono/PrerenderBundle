<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Prerenderer\Adapter;

use Setono\PrerenderBundle\Prerenderer\AbstractPrerenderer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Rendertron extends AbstractPrerenderer
{
    private HttpClientInterface $httpClient;

    private string $rendertronUrl;

    public function __construct(RequestStack $requestStack, HttpClientInterface $httpClient, string $rendertronUrl)
    {
        parent::__construct($requestStack);

        $this->httpClient = $httpClient;
        $this->rendertronUrl = rtrim($rendertronUrl, '/');
    }

    public function renderUrl(string $url): string
    {
        return $this->httpClient
            ->request('GET', sprintf('%s/render/%s', $this->rendertronUrl, rawurlencode($url)))
            ->getContent()
        ;
    }
}
