<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\CustomerInfo\Service;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerInfo\DTO\CustomerGroupCountInterface;

interface CustomerGroupServiceInterface
{
    /** @return list<CustomerGroupCountInterface> */
    public function getCustomerGroupCounts(): array;

    public function getTotalCustomerCount(): int;
}
