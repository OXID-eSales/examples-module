<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\DTO;

readonly class CustomerGroupCount implements CustomerGroupCountInterface
{
    public function __construct(
        private string $groupId,
        private string $title,
        private int $count,
    ) {
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
