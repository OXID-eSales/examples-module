<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\CustomerGroup\Controller;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Controller\CustomerGroupApiController;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\DataObject\CustomerGroupCount;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Service\CustomerGroupServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

#[CoversClass(CustomerGroupApiController::class)]
final class CustomerGroupApiControllerTest extends TestCase
{
    public function testGetCustomerGroupsReturnsJsonResponse(): void
    {
        $sut = $this->getSut();

        $this->assertInstanceOf(
            JsonResponse::class,
            $sut->getCustomerGroups()
        );
    }

    public function testGetCustomerGroupsReturnsStatus200(): void
    {
        $sut = $this->getSut();

        $this->assertSame(200, $sut->getCustomerGroups()->getStatusCode());
    }

    public function testGetCustomerGroupsContainsGroupData(): void
    {
        $groupId = uniqid('group_', true);
        $title = uniqid('title_', true);
        $count = mt_rand(1, 500);

        $serviceStub = $this->createStub(CustomerGroupServiceInterface::class);
        $serviceStub->method('getCustomerGroupCounts')
            ->willReturn([
                new CustomerGroupCount(
                    groupId: $groupId,
                    title: $title,
                    count: $count,
                ),
            ]);
        $serviceStub->method('getTotalCustomerCount')
            ->willReturn($count);

        $sut = $this->getSut(customerGroupService: $serviceStub);

        $data = $this->decodeResponse($sut->getCustomerGroups());

        $this->assertCount(1, $data['customerGroups']);
        $this->assertSame($groupId, $data['customerGroups'][0]['groupId']);
        $this->assertSame($title, $data['customerGroups'][0]['title']);
        $this->assertSame($count, $data['customerGroups'][0]['count']);
    }

    public function testGetCustomerGroupsContainsTotalCount(): void
    {
        $total = mt_rand(100, 5000);

        $serviceStub = $this->createStub(CustomerGroupServiceInterface::class);
        $serviceStub->method('getCustomerGroupCounts')
            ->willReturn([]);
        $serviceStub->method('getTotalCustomerCount')
            ->willReturn($total);

        $sut = $this->getSut(customerGroupService: $serviceStub);

        $data = $this->decodeResponse($sut->getCustomerGroups());

        $this->assertSame($total, $data['total']);
    }

    public function testGetCustomerGroupsResponseStructure(): void
    {
        $sut = $this->getSut();

        $data = $this->decodeResponse($sut->getCustomerGroups());

        $this->assertArrayHasKey('customerGroups', $data);
        $this->assertArrayHasKey('total', $data);
        $this->assertCount(2, $data);
    }

    private function getSut(
        ?CustomerGroupServiceInterface $customerGroupService = null,
    ): CustomerGroupApiController {
        return new CustomerGroupApiController(
            customerGroupService: $customerGroupService
                ?? $this->createStub(CustomerGroupServiceInterface::class),
        );
    }

    private function decodeResponse(JsonResponse $response): array
    {
        return json_decode($response->getContent(), true);
    }
}
