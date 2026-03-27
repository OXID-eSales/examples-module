<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\UserInfo\DataObject;

use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\DataObject\UserInfo;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UserInfo::class)]
final class UserInfoTest extends TestCase
{
    public function testGetFirstName(): void
    {
        $firstName = uniqid('name_', true);

        $sut = new UserInfo(
            firstName: $firstName,
            greetingUrl: uniqid(),
        );

        $this->assertSame($firstName, $sut->getFirstName());
    }

    public function testGetGreetingUrl(): void
    {
        $greetingUrl = uniqid('url_', true);

        $sut = new UserInfo(
            firstName: uniqid(),
            greetingUrl: $greetingUrl,
        );

        $this->assertSame($greetingUrl, $sut->getGreetingUrl());
    }
}
