<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\CustomerGroup\Service;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Infrastructure\CustomerGroupRepositoryInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\DTO\CustomerGroupCountInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Service\CustomerGroupService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CustomerGroupService::class)]
final class CustomerGroupServiceTest extends TestCase
{
    public function testGetCustomerGroupCountsDelegatesToRepository(): void
    {
        $expectedCounts = [
            $this->createStub(CustomerGroupCountInterface::class),
            $this->createStub(CustomerGroupCountInterface::class),
        ];

        $repositoryStub = $this->createStub(CustomerGroupRepositoryInterface::class);
        $repositoryStub->method('getCustomerGroupCounts')
            ->willReturn($expectedCounts);

        $sut = $this->getSut(groupCountRepository: $repositoryStub);

        $this->assertSame($expectedCounts, $sut->getCustomerGroupCounts());
    }

    public function testGetCustomerGroupCountsReturnsEmptyArrayWhenNoGroups(): void
    {
        $repositoryStub = $this->createStub(CustomerGroupRepositoryInterface::class);
        $repositoryStub->method('getCustomerGroupCounts')
            ->willReturn([]);

        $sut = $this->getSut(groupCountRepository: $repositoryStub);

        $this->assertSame([], $sut->getCustomerGroupCounts());
    }

    public function testGetTotalCustomerCountSumsAllGroups(): void
    {
        $count1 = mt_rand(1, 500);
        $count2 = mt_rand(1, 500);

        $group1 = $this->createConfiguredStub(CustomerGroupCountInterface::class, ['getCount' => $count1]);
        $group2 = $this->createConfiguredStub(CustomerGroupCountInterface::class, ['getCount' => $count2]);

        $repositoryStub = $this->createStub(CustomerGroupRepositoryInterface::class);
        $repositoryStub->method('getCustomerGroupCounts')
            ->willReturn([$group1, $group2]);

        $sut = $this->getSut(groupCountRepository: $repositoryStub);

        $this->assertSame($count1 + $count2, $sut->getTotalCustomerCount());
    }

    public function testGetTotalCustomerCountReturnsZeroWhenNoGroups(): void
    {
        $repositoryStub = $this->createStub(CustomerGroupRepositoryInterface::class);
        $repositoryStub->method('getCustomerGroupCounts')
            ->willReturn([]);

        $sut = $this->getSut(groupCountRepository: $repositoryStub);

        $this->assertSame(0, $sut->getTotalCustomerCount());
    }

    private function getSut(
        ?CustomerGroupRepositoryInterface $groupCountRepository = null,
    ): CustomerGroupService {
        $groupCountRepository ??= $this->createStub(CustomerGroupRepositoryInterface::class);

        return new CustomerGroupService(
            groupCountRepository: $groupCountRepository,
        );
    }
}
