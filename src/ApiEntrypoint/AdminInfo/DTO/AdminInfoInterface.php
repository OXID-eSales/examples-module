<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\DTO;

interface AdminInfoInterface
{
    public function getEmail(): string;

    public function getGreeting(): string;
}
