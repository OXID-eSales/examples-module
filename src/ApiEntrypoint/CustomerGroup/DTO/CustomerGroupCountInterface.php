<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\DTO;

interface CustomerGroupCountInterface
{
    public function getGroupId(): string;

    public function getTitle(): string;

    public function getCount(): int;
}
