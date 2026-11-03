<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\CustomerInfo\Service;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerInfo\Infrastructure\CustomerGroupRepositoryInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerInfo\DTO\CustomerGroupCountInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerInfo\Service\CustomerGroupService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CustomerGroupServiceTest extends TestCase
{
    #[Test]
    public function getCustomerGroupCountsDelegatesToRepository(): void
    {
        $expectedCounts = [
            $this->createStub(CustomerGroupCountInterface::class),
            $this->createStub(CustomerGroupCountInterface::class),
        ];

        $repositoryStub = $this->createStub(CustomerGroupRepositoryInterface::class);
        $repositoryStub->method('getCustomerGroupCounts')
            ->willReturn($expectedCounts);

        $sut = $this->getSut(
            groupCountRepository: $repositoryStub,
        );

        $this->assertSame($expectedCounts, $sut->getCustomerGroupCounts());
    }

    #[Test]
    public function getCustomerGroupCountsReturnsEmptyArrayWhenNoGroups(): void
    {
        $repositoryStub = $this->createStub(CustomerGroupRepositoryInterface::class);
        $repositoryStub->method('getCustomerGroupCounts')
            ->willReturn([]);

        $sut = $this->getSut(
            groupCountRepository: $repositoryStub,
        );

        $this->assertSame([], $sut->getCustomerGroupCounts());
    }

    #[Test]
    public function getTotalCustomerCountSumsAllGroups(): void
    {
        $count1 = mt_rand(1, 500);
        $count2 = mt_rand(1, 500);

        $group1 = $this->createConfiguredStub(CustomerGroupCountInterface::class, ['getCount' => $count1]);
        $group2 = $this->createConfiguredStub(CustomerGroupCountInterface::class, ['getCount' => $count2]);

        $repositoryStub = $this->createStub(CustomerGroupRepositoryInterface::class);
        $repositoryStub->method('getCustomerGroupCounts')
            ->willReturn([$group1, $group2]);

        $sut = $this->getSut(
            groupCountRepository: $repositoryStub,
        );

        $this->assertSame($count1 + $count2, $sut->getTotalCustomerCount());
    }

    #[Test]
    public function getTotalCustomerCountReturnsZeroWhenNoGroups(): void
    {
        $repositoryStub = $this->createStub(CustomerGroupRepositoryInterface::class);
        $repositoryStub->method('getCustomerGroupCounts')
            ->willReturn([]);

        $sut = $this->getSut(
            groupCountRepository: $repositoryStub,
        );

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
