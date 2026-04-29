<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\CustomerGroup\Service;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Infrastructure\CustomerGroupCountRepositoryInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\DataObject\CustomerGroupCount;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Service\CustomerGroupService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CustomerGroupService::class)]
final class CustomerGroupServiceTest extends TestCase
{
    public function testGetCustomerGroupCountsDelegatesToRepository(): void
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

        $repositoryStub = $this->createStub(CustomerGroupCountRepositoryInterface::class);
        $repositoryStub->method('getCustomerGroupCounts')
            ->willReturn($expectedCounts);

        $sut = $this->getSut(groupCountRepository: $repositoryStub);

        $this->assertSame($expectedCounts, $sut->getCustomerGroupCounts());
    }

    public function testGetCustomerGroupCountsReturnsEmptyArrayWhenNoGroups(): void
    {
        $repositoryStub = $this->createStub(CustomerGroupCountRepositoryInterface::class);
        $repositoryStub->method('getCustomerGroupCounts')
            ->willReturn([]);

        $sut = $this->getSut(groupCountRepository: $repositoryStub);

        $this->assertSame([], $sut->getCustomerGroupCounts());
    }

    public function testGetTotalCustomerCountSumsAllGroups(): void
    {
        $count1 = mt_rand(1, 500);
        $count2 = mt_rand(1, 500);

        $repositoryStub = $this->createStub(CustomerGroupCountRepositoryInterface::class);
        $repositoryStub->method('getCustomerGroupCounts')
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

        $sut = $this->getSut(groupCountRepository: $repositoryStub);

        $this->assertSame($count1 + $count2, $sut->getTotalCustomerCount());
    }

    public function testGetTotalCustomerCountReturnsZeroWhenNoGroups(): void
    {
        $repositoryStub = $this->createStub(CustomerGroupCountRepositoryInterface::class);
        $repositoryStub->method('getCustomerGroupCounts')
            ->willReturn([]);

        $sut = $this->getSut(groupCountRepository: $repositoryStub);

        $this->assertSame(0, $sut->getTotalCustomerCount());
    }

    private function getSut(
        ?CustomerGroupCountRepositoryInterface $groupCountRepository = null,
    ): CustomerGroupService {
        $groupCountRepository ??= $this->createStub(CustomerGroupCountRepositoryInterface::class);

        return new CustomerGroupService(
            groupCountRepository: $groupCountRepository,
        );
    }
}
