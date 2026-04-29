<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\UserInfo\Service;

use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Infrastructure\UserRepositoryInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\DTO\UserInfoInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Service\UserInfoService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UserInfoService::class)]
final class UserInfoServiceTest extends TestCase
{
    public function testGetUserInfoReturnsUserInfoWithFirstNameAndGreetingUrl(): void
    {
        $username = uniqid('user_');
        $expectedFirstName = uniqid('name_');

        $repositoryStub = $this->createConfiguredStub(UserRepositoryInterface::class, [
            'getFirstNameByUsername' => $expectedFirstName,
        ]);

        $sut = $this->getSut(userRepository: $repositoryStub);
        $result = $sut->getUserInfo($username);

        $this->assertInstanceOf(UserInfoInterface::class, $result);
        $this->assertSame($expectedFirstName, $result->getFirstName());
        $this->assertSame('index.php?cl=oeem_greeting', $result->getGreetingUrl());
    }

    public function testGetUserInfoReturnsNullWhenUserNotFound(): void
    {
        $username = uniqid('unknown_');

        $repositoryStub = $this->createStub(UserRepositoryInterface::class);
        $repositoryStub->method('getFirstNameByUsername')
            ->with($username)
            ->willReturn(null);

        $sut = $this->getSut(userRepository: $repositoryStub);

        $this->assertNull($sut->getUserInfo($username));
    }

    private function getSut(
        ?UserRepositoryInterface $userRepository = null,
    ): UserInfoService {
        $userRepository ??= $this->createStub(UserRepositoryInterface::class);

        return new UserInfoService(
            userRepository: $userRepository,
        );
    }
}
