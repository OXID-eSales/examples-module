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
    public function testGetUserInfoReturnsFirstNameFromDao(): void
    {
        $username = uniqid('user_', true);
        $expectedFirstName = uniqid('name_', true);

        $daoStub = $this->createStub(SessionUserDaoInterface::class);
        $daoStub->method('getFirstNameByUsername')
            ->with($username)
            ->willReturn($expectedFirstName);

        $sut = $this->getSut(sessionUserDao: $daoStub);

        $result = $sut->getUserInfo($username);

        $this->assertSame($expectedFirstName, $result->getFirstName());
    }

    public function testGetUserInfoReturnsGreetingUrl(): void
    {
        $username = uniqid('user_', true);

        $daoStub = $this->createStub(SessionUserDaoInterface::class);
        $daoStub->method('getFirstNameByUsername')
            ->willReturn(uniqid());

        $sut = $this->getSut(sessionUserDao: $daoStub);

        $result = $sut->getUserInfo($username);

        $this->assertSame(
            'index.php?cl=oeem_greeting',
            $result->getGreetingUrl()
        );
    }

    public function testGetUserInfoReturnsNullWhenUserNotFound(): void
    {
        $username = uniqid('unknown_', true);

        $daoStub = $this->createStub(SessionUserDaoInterface::class);
        $daoStub->method('getFirstNameByUsername')
            ->with($username)
            ->willReturn(null);

        $sut = $this->getSut(sessionUserDao: $daoStub);

        $this->assertNull($sut->getUserInfo($username));
    }

    private function getSut(
        ?SessionUserDaoInterface $sessionUserDao = null,
    ): UserInfoService {
        return new UserInfoService(
            sessionUserDao: $sessionUserDao
                ?? $this->createStub(SessionUserDaoInterface::class),
        );
    }
}
