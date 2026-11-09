<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Greeting\Model;

use OxidEsales\Eshop\Application\Model\User as EshopModelUser;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\GreetingRepository;
use OxidEsales\ExamplesModule\Greeting\Model\PersonalGreetingUser;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;

#[CoversTrait(PersonalGreetingUser::class)]
final class PersonalGreetingUserTest extends IntegrationTestCase
{
    #[Test]
    public function getPersonalGreetingNotSet(): void
    {
        $user = oxNew(EshopModelUser::class);

        $this->assertEmpty($user->getPersonalGreeting());
    }

    #[Test]
    public function getPersonalGreeting(): void
    {
        $user = oxNew(EshopModelUser::class);

        $exampleGreeting = uniqid('greeting');
        $user->assign([
            GreetingRepository::OEEM_USER_GREETING_FIELD => $exampleGreeting,
        ]);

        $this->assertSame($exampleGreeting, $user->getPersonalGreeting());
    }
}
