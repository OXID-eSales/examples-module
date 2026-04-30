<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\CustomerGroup\Controller;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Controller\CustomerGroupApiController;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\DTO\CustomerGroupCountInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Service\CustomerGroupServiceInterface;
use OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\ApiEntrypointTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\JsonResponse;

#[CoversClass(CustomerGroupApiController::class)]
final class CustomerGroupApiControllerTest extends ApiEntrypointTestCase
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
        $groupId = uniqid('group_');
        $title = uniqid('title_');
        $count = mt_rand(1, 500);
        $total = mt_rand(100, 5000);

        $groupStub = $this->createConfiguredStub(CustomerGroupCountInterface::class, [
            'getGroupId' => $groupId,
            'getTitle' => $title,
            'getCount' => $count,
        ]);

        $serviceStub = $this->createConfiguredStub(CustomerGroupServiceInterface::class, [
            'getCustomerGroupCounts' => [$groupStub],
            'getTotalCustomerCount' => $total,
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
}
