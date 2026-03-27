<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\CustomerGroup\DataObject;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\DataObject\CustomerGroupCount;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CustomerGroupCount::class)]
final class CustomerGroupCountTest extends TestCase
{
    public function testGetGroupId(): void
    {
        $groupId = uniqid('group_', true);

        $sut = new CustomerGroupCount(
            groupId: $groupId,
            title: uniqid(),
            count: mt_rand(0, 100),
        );

        $this->assertSame($groupId, $sut->getGroupId());
    }

    public function testGetTitle(): void
    {
        $title = uniqid('title_', true);

        $sut = new CustomerGroupCount(
            groupId: uniqid(),
            title: $title,
            count: mt_rand(0, 100),
        );

        $this->assertSame($title, $sut->getTitle());
    }

    public function testGetCount(): void
    {
        $count = mt_rand(0, 10000);

        $sut = new CustomerGroupCount(
            groupId: uniqid(),
            title: uniqid(),
            count: $count,
        );

        $this->assertSame($count, $sut->getCount());
    }
}
