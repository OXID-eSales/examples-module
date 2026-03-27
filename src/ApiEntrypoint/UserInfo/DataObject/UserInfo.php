<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\DataObject;

readonly class UserInfo
{
    public function __construct(
        private string $firstName,
        private string $greetingUrl,
    ) {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getGreetingUrl(): string
    {
        return $this->greetingUrl;
    }
}
