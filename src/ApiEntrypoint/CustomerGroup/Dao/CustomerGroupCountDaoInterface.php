<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Dao;

use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\DataObject\CustomerGroupCount;

interface CustomerGroupCountDaoInterface
{
    /** @return list<CustomerGroupCount> */
    public function getCustomerGroupCounts(): array;
}
