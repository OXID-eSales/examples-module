<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Service;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\DTO\CustomerGroupCountInterface;

interface CustomerGroupServiceInterface
{
    /** @return list<CustomerGroupCountInterface> */
    public function getCustomerGroupCounts(): array;

    public function getTotalCustomerCount(): int;
}
