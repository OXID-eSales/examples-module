<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Greeting\Infrastructure\Factory;

use OxidEsales\ExamplesModule\Extension\Model\UserInterface;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Factory\UserModelFactory;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Factory\UserModelFactoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(UserModelFactory::class)]
class UserModelFactoryTest extends TestCase
{
    #[Test]
    public function createProducesCorrectTypeOfObjects(): void
    {
        $sut = $this->getSut();

        $user = $sut->create();
        $this->assertInstanceOf(UserInterface::class, $user);
    }

    #[Test]
    public function createProducesNewObjectsWithEveryCall(): void
    {
        $sut = $this->getSut();

        $user1 = $sut->create();
        $user2 = $sut->create();

        $this->assertNotSame($user1, $user2);
    }

    private function getSut(): UserModelFactoryInterface
    {
        return new UserModelFactory();
    }
}
