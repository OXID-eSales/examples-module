<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Service;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Infrastructure\CustomerGroupRepositoryInterface;

readonly class CustomerGroupService implements CustomerGroupServiceInterface
{
    public function __construct(
        private CustomerGroupRepositoryInterface $groupCountRepository,
    ) {
    }

    /** @inheritDoc */
    public function getCustomerGroupCounts(): array
    {
        return $this->groupCountRepository->getCustomerGroupCounts();
    }

    public function getTotalCustomerCount(): int
    {
        $total = 0;
        foreach ($this->groupCountRepository->getCustomerGroupCounts() as $group) {
            $total += $group->getCount();
        }

        return $total;
    }
}
