<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Greeting\Infrastructure\Repository;

use OxidEsales\ExamplesModule\Extension\Model\User;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Factory\UserModelFactoryInterface;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\UserRepository;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\UserRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UserRepository::class)]
final class UserRepositoryTest extends TestCase
{
    public function testGetUserByIdLoadsUserAndReturnsIt(): void
    {
        $userId = uniqid();

        $userModelSpy = $this->createMock(User::class);
        $userModelSpy->expects($this->once())
            ->method('load')
            ->with($userId);

        $userModelFactoryMock = $this->createConfiguredStub(UserModelFactoryInterface::class, [
            'create' => $userModelSpy,
        ]);

        $sut = $this->getSut(
            userModelFactory: $userModelFactoryMock
        );

        $result = $sut->getUserById($userId);
        $this->assertSame($userModelSpy, $result);
    }

    private function getSut(
        ?UserModelFactoryInterface $userModelFactory = null,
    ): UserRepositoryInterface {
        $userModelFactory ??= $this->createStub(UserModelFactoryInterface::class);

        return new UserRepository($userModelFactory);
    }
}
