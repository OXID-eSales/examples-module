<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Greeting\Service;

use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\Extension\Model\User;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\UserModelFactoryInterface;
use OxidEsales\ExamplesModule\Greeting\Service\UserService;
use PHPUnit\Framework\Attributes\Test;

final class UserServiceTest extends IntegrationTestCase
{
    public function setUp(): void
    {
        // @todo: its a temporary hack for broken autoloader solution 9474. Remove this when the case is solved.
        oxNew(User::class);
    }

    #[Test]
    public function getUserByIdLoadsUserAndReturnsIt(): void
    {
        $userId = uniqid();

        $userModelSpy = $this->createMock(User::class);
        $userModelSpy->expects($this->once())
            ->method('load')
            ->with($userId);

        $userModelFactoryMock = $this->createConfiguredStub(UserModelFactoryInterface::class, [
            'create' => $userModelSpy,
        ]);

        $sut = new UserService(
            userModelFactory: $userModelFactoryMock
        );

        $result = $sut->getUserById($userId);
        $this->assertSame($userModelSpy, $result);
    }
}
