<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\CustomerGroup\Service;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Dao\CustomerGroupCountDaoInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\DataObject\CustomerGroupCount;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Service\CustomerGroupService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CustomerGroupService::class)]
final class CustomerGroupServiceTest extends TestCase
{
    public function testGetCustomerGroupCountsDelegatesToDao(): void
    {
        $expectedCounts = [
            new CustomerGroupCount(
                groupId: uniqid('group_'),
                title: uniqid('title_'),
                count: mt_rand(1, 500),
            ),
            new CustomerGroupCount(
                groupId: uniqid('group_'),
                title: uniqid('title_'),
                count: mt_rand(1, 500),
            ),
        ];

        $daoStub = $this->createStub(CustomerGroupCountDaoInterface::class);
        $daoStub->method('getCustomerGroupCounts')
            ->willReturn($expectedCounts);

        $sut = $this->getSut(groupCountDao: $daoStub);

        $this->assertSame($expectedCounts, $sut->getCustomerGroupCounts());
    }

    public function testGetCustomerGroupCountsReturnsEmptyArrayWhenNoGroups(): void
    {
        $daoStub = $this->createStub(CustomerGroupCountDaoInterface::class);
        $daoStub->method('getCustomerGroupCounts')
            ->willReturn([]);

        $sut = $this->getSut(groupCountDao: $daoStub);

        $this->assertSame([], $sut->getCustomerGroupCounts());
    }

    public function testGetTotalCustomerCountSumsAllGroups(): void
    {
        $count1 = mt_rand(1, 500);
        $count2 = mt_rand(1, 500);

        $daoStub = $this->createStub(CustomerGroupCountDaoInterface::class);
        $daoStub->method('getCustomerGroupCounts')
            ->willReturn([
                new CustomerGroupCount(
                    groupId: uniqid(),
                    title: uniqid(),
                    count: $count1,
                ),
                new CustomerGroupCount(
                    groupId: uniqid(),
                    title: uniqid(),
                    count: $count2,
                ),
            ]);

        $sut = $this->getSut(groupCountDao: $daoStub);

        $this->assertSame($count1 + $count2, $sut->getTotalCustomerCount());
    }

    public function testGetTotalCustomerCountReturnsZeroWhenNoGroups(): void
    {
        $daoStub = $this->createStub(CustomerGroupCountDaoInterface::class);
        $daoStub->method('getCustomerGroupCounts')
            ->willReturn([]);

        $sut = $this->getSut(groupCountDao: $daoStub);

        $this->assertSame(0, $sut->getTotalCustomerCount());
    }

    private function getSut(
        ?CustomerGroupCountDaoInterface $groupCountDao = null,
    ): CustomerGroupService {
        $groupCountDao ??= $this->createStub(CustomerGroupCountDaoInterface::class);

        return new CustomerGroupService(
            groupCountDao: $groupCountDao,
        );
    }
}
