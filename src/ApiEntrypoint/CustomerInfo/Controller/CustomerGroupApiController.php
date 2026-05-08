<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\CustomerInfo\Controller;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerInfo\Service\CustomerGroupServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

readonly class CustomerGroupApiController
{
    public function __construct(
        private CustomerGroupServiceInterface $customerGroupService,
    ) {
    }

    #[Route('/api/customer-groups', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getCustomerGroups(): JsonResponse
    {
        $groups = $this->customerGroupService->getCustomerGroupCounts();

        $groupsData = [];
        foreach ($groups as $group) {
            $groupsData[] = [
                'groupId' => $group->getGroupId(),
                'title' => $group->getTitle(),
                'count' => $group->getCount(),
            ];
        }

        return new JsonResponse([
            'customerGroups' => $groupsData,
            'total' => $this->customerGroupService->getTotalCustomerCount(),
        ]);
    }
}
