<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Infrastructure;

interface UserRepositoryInterface
{
    public function getFirstNameByUsername(string $username): ?string;
}
