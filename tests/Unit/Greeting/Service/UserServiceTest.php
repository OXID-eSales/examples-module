<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Greeting\Service;

use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\UserRepositoryInterface;
use OxidEsales\ExamplesModule\Greeting\Model\PersonalGreetingUserInterface;
use OxidEsales\ExamplesModule\Greeting\Service\UserService;
use OxidEsales\ExamplesModule\Greeting\Service\UserServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UserService::class)]
final class UserServiceTest extends TestCase
{
    public function testGetUserByIdDelegatesToRepository(): void
    {
        $userId = uniqid();
        $userModel = $this->createStub(PersonalGreetingUserInterface::class);

        $userRepositoryMock = $this->createConfiguredMock(UserRepositoryInterface::class, [
            'getUserById' => $userModel,
        ]);
        $userRepositoryMock->expects($this->once())
            ->method('getUserById')
            ->with($userId);

        $sut = $this->getSut(
            userRepository: $userRepositoryMock
        );

        $result = $sut->getUserById($userId);
        $this->assertSame($userModel, $result);
    }

    private function getSut(
        ?UserRepositoryInterface $userRepository = null,
    ): UserServiceInterface {
        $userRepository ??= $this->createStub(UserRepositoryInterface::class);

        return new UserService($userRepository);
    }
}
