<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\UserInfo\Service;

use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Dao\SessionUserDaoInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\DataObject\UserInfo;
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

        $daoStub = $this->createConfiguredStub(SessionUserDaoInterface::class, [
            'getFirstNameByUsername' => $expectedFirstName,
        ]);

        $sut = $this->getSut(sessionUserDao: $daoStub);
        $result = $sut->getUserInfo($username);

        $this->assertInstanceOf(UserInfo::class, $result);
        $this->assertSame($expectedFirstName, $result->getFirstName());
        $this->assertSame('index.php?cl=oeem_greeting', $result->getGreetingUrl());
    }

    public function testGetUserInfoReturnsNullWhenUserNotFound(): void
    {
        $username = uniqid('unknown_');

        $daoMock = $this->createStub(SessionUserDaoInterface::class);
        $daoMock->method('getFirstNameByUsername')
            ->with($username)
            ->willReturn(null);

        $sut = $this->getSut(sessionUserDao: $daoMock);

        $this->assertNull($sut->getUserInfo($username));
    }

    private function getSut(
        ?SessionUserDaoInterface $sessionUserDao = null,
    ): UserInfoService {
        $sessionUserDao ??= $this->createStub(SessionUserDaoInterface::class);

        return new UserInfoService(
            sessionUserDao: $sessionUserDao,
        );
    }
}
