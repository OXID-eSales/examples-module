<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\UserInfo\DTO;

use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\DTO\UserInfo;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UserInfo::class)]
final class UserInfoTest extends TestCase
{
    public function testStoresFirstNameAndGreetingUrl(): void
    {
        $firstName = uniqid('name_');
        $greetingUrl = uniqid('url_');

        $sut = new UserInfo(
            firstName: $firstName,
            greetingUrl: $greetingUrl,
        );

        $this->assertSame($firstName, $sut->getFirstName());
        $this->assertSame($greetingUrl, $sut->getGreetingUrl());
    }
}
