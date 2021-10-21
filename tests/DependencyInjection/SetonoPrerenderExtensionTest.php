<?php

declare(strict_types=1);

namespace Setono\PrerenderBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Setono\PrerenderBundle\DependencyInjection\SetonoPrerenderExtension;

/**
 * @covers \Setono\PrerenderBundle\DependencyInjection\SetonoPrerenderExtension
 */
final class SetonoPrerenderExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new SetonoPrerenderExtension(),
        ];
    }

    /**
     * @test
     */
    public function it_can_load(): void
    {
        $this->load();

        self::assertTrue(true);
    }
}
