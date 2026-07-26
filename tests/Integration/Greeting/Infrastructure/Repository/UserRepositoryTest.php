<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Greeting\Infrastructure\Repository;

use OxidEsales\ExamplesModule\Extension\Model\User;
use OxidEsales\ExamplesModule\Extension\Model\UserInterface;
use OxidEsales\ExamplesModule\Greeting\Exception\UserNotLoggedIn;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Factory\UserModelFactoryInterface;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\UserRepository;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\UserRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
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

    #[Test]
    public function getActiveUserReturnsUserIfLoadingSuccessful(): void
    {
        $userModelFactoryMock = $this->createConfiguredStub(UserModelFactoryInterface::class, [
            'create' => $userModelStub = $this->createStub(User::class),
        ]);

        $userModelStub->method('loadActiveUser')->willReturn(true);

        $sut = $this->getSut(
            userModelFactory: $userModelFactoryMock
        );

        $result = $sut->getActiveUser();

        $this->assertSame($userModelStub, $result);
        $this->assertInstanceOf(UserInterface::class, $result);
    }

    #[Test]
    public function getActiveUserThrowsExceptionIfLoadingNotSuccessful(): void
    {
        $userModelFactoryMock = $this->createConfiguredStub(UserModelFactoryInterface::class, [
            'create' => $userModelStub = $this->createStub(User::class),
        ]);

        $userModelStub->method('loadActiveUser')->willReturn(false);

        $sut = $this->getSut(
            userModelFactory: $userModelFactoryMock
        );

        $this->expectException(UserNotLoggedIn::class);
        $sut->getActiveUser();
    }

    private function getSut(
        ?UserModelFactoryInterface $userModelFactory = null,
    ): UserRepositoryInterface {
        $userModelFactory ??= $this->createStub(UserModelFactoryInterface::class);

        return new UserRepository($userModelFactory);
    }
}
