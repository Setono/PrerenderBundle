# Symfony Prerender Bundle

[![Latest Version][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]

Use this bundle to (pre)render a request or URL. This is useful if you render your page using javascript and want to show
the HTML output of that page to Googlebot or other clients not very fond of javascript.

See Googles own help page on this topic: [Implement dynamic rendering](https://developers.google.com/search/docs/advanced/javascript/dynamic-rendering).

## Installation

To install this bundle, simply run:

```shell
composer require setono/prerender-bundle
```

This will install the bundle and enable it if you're using Symfony Flex. If you're not using Flex, add the bundle
manually to `bundles.php` instead.

## Configuration

```yaml
# config/packages/setono_prerender.yaml
setono_prerender:
    prerenderer:
        rendertron:
            url: <the url of your rendertron service> # default is http://localhost:3000
```

## Usage

Here is a very basic example of a product controller where we want to render the index (i.e. a product listing page).

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Setono\PrerenderBundle\Prerenderer\PrerendererInterface;
use Symfony\Component\HttpFoundation\Response;

final class ProductController
{
    public function index(PrerendererInterface $prerenderer): Response
    {
        if($this->isBot()) {
            // return the rendered HTML if the client is a bot
            return new Response($prerenderer->renderMainRequest());
        }

        // render the response the normal way if the client is NOT a bot
        return new Response('...');
    }

    /**
     * Method that returns true if the client is a bot
     */
    private function isBot(): bool
    {
        // ...
    }
}
```

[ico-version]: https://poser.pugx.org/setono/prerender-bundle/v/stable
[ico-license]: https://poser.pugx.org/setono/prerender-bundle/license
[ico-github-actions]: https://github.com/Setono/PrerenderBundle/workflows/build/badge.svg

[link-packagist]: https://packagist.org/packages/setono/prerender-bundle
[link-github-actions]: https://github.com/Setono/PrerenderBundle/actions
