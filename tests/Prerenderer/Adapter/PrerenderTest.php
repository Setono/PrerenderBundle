<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Tests\Prerenderer\Adapter;

use PHPUnit\Framework\TestCase;
use Setono\PrerenderBundle\Prerenderer\Adapter\Prerender;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Process\Process;

final class PrerenderTest extends TestCase
{
    private static ?Process $server = null;

    public static function setUpBeforeClass(): void
    {
        $cwd = __DIR__ . '/prerender_server';
        (new Process(['npm', 'install'], $cwd))->run();

        self::$server = new Process(['node', 'server.js'], $cwd);
        self::$server->start();

        /** @psalm-suppress MissingClosureParamType,UnusedClosureParam */
        self::$server->waitUntil(function ($type, $output): bool {
            return is_string($output) && false !== strpos($output, 'Started Chrome');
        });
    }

    public static function tearDownAfterClass(): void
    {
        if (null !== self::$server) {
            self::$server->stop();
        }
    }

    /**
     * @test
     */
    public function it_renders(): void
    {
        $adapter = new Prerender(new RequestStack(), HttpClient::create());

        self::assertStringContainsString('<title>Google</title>', $adapter->renderUrl('https://www.google.com/'));
    }
}
