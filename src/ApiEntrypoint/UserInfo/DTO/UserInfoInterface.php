<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\DTO;

interface UserInfoInterface
{
    public function getFirstName(): string;

    public function getGreetingUrl(): string;
}
