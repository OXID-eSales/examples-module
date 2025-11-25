<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Extension\Model;

use OxidEsales\Eshop\Application\Model\User as EshopModelUser;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\Extension\Model\User;
use OxidEsales\ExamplesModule\Extension\Model\UserInterface;
use OxidEsales\ExamplesModule\Greeting\Model\PersonalGreetingUserInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(User::class)]
final class UserTest extends IntegrationTestCase
{
    public function testImplementsInterfaces(): void
    {
        $user = oxNew(EshopModelUser::class);

        self::assertInstanceOf(UserInterface::class, $user);
        self::assertInstanceOf(PersonalGreetingUserInterface::class, $user);
    }

    #[Test]
    public function getIdWorksAsIntended(): void
    {
        $user = oxNew(EshopModelUser::class);
        $this->assertNull($user->getId());

        $user->setId($randomId = uniqid());
        $this->assertSame($randomId, $user->getId());
    }
}
