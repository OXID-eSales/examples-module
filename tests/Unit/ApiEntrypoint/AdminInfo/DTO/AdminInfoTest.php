<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\AdminInfo\DTO;

use OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\DTO\AdminInfo;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AdminInfo::class)]
final class AdminInfoTest extends TestCase
{
    public function testStoresEmailAndGreeting(): void
    {
        $email = uniqid() . '@example.com';
        $greeting = uniqid();

        $sut = new AdminInfo(email: $email, greeting: $greeting);

        $this->assertSame($email, $sut->getEmail());
        $this->assertSame($greeting, $sut->getGreeting());
    }
}
