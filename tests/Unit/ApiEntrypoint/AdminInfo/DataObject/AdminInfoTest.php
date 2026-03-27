<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\AdminInfo\DataObject;

use OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\DataObject\AdminInfo;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AdminInfo::class)]
final class AdminInfoTest extends TestCase
{
    public function testGetEmail(): void
    {
        $email = uniqid('admin_', true) . '@example.com';

        $sut = new AdminInfo(
            email: $email,
            greeting: uniqid(),
        );

        $this->assertSame($email, $sut->getEmail());
    }

    public function testGetGreeting(): void
    {
        $greeting = uniqid('greeting_', true);

        $sut = new AdminInfo(
            email: uniqid() . '@example.com',
            greeting: $greeting,
        );

        $this->assertSame($greeting, $sut->getGreeting());
    }
}
