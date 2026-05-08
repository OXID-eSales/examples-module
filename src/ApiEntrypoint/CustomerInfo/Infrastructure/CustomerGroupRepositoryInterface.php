<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\CustomerInfo\Infrastructure;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerInfo\DTO\CustomerGroupCountInterface;

interface CustomerGroupRepositoryInterface
{
    /** @return list<CustomerGroupCountInterface> */
    public function getCustomerGroupCounts(): array;
}
