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
    public function testGetCustomerGroupsReturnsJsonResponseWithStatus200(): void
    {
        $sut = $this->getSut();
        $response = $sut->getCustomerGroups();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testGetCustomerGroupsContainsGroupDataAndTotal(): void
    {
        $serviceStub = $this->createConfiguredStub(CustomerGroupServiceInterface::class, [
            'getCustomerGroupCounts' => [
                new CustomerGroupCount(
                    groupId: $groupId = uniqid('group_'),
                    title: $title = uniqid('title_'),
                    count: $count = mt_rand(1, 500)
                )
            ],
            'getTotalCustomerCount' => $total = mt_rand(100, 5000),
        ]);

        $sut = $this->getSut(customerGroupService: $serviceStub);
        $response = $sut->getCustomerGroups();
        $data = $this->decodeResponse($response);

        $this->assertCount(1, $data['customerGroups']);
        $this->assertSame($groupId, $data['customerGroups'][0]['groupId']);
        $this->assertSame($title, $data['customerGroups'][0]['title']);
        $this->assertSame($count, $data['customerGroups'][0]['count']);
        $this->assertSame($total, $data['total']);
    }

    private function getSut(
        ?CustomerGroupServiceInterface $customerGroupService = null,
    ): CustomerGroupApiController {
        $customerGroupService ??= $this->createStub(CustomerGroupServiceInterface::class);

        return new CustomerGroupApiController(
            customerGroupService: $customerGroupService,
        );
    }

    private function decodeResponse(JsonResponse $response): array
    {
        return json_decode($response->getContent(), true);
    }
}
