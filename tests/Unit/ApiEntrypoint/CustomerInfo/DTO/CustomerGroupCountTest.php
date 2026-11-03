<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\CustomerInfo\DTO;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerInfo\DTO\CustomerGroupCount;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CustomerGroupCountTest extends TestCase
{
    #[Test]
    public function storesGroupIdTitleAndCount(): void
    {
        $groupId = uniqid();
        $title = uniqid();
        $count = mt_rand(0, 10000);

        $sut = new CustomerGroupCount(groupId: $groupId, title: $title, count: $count);

        $this->assertSame($groupId, $sut->getGroupId());
        $this->assertSame($title, $sut->getTitle());
        $this->assertSame($count, $sut->getCount());
    }
}
