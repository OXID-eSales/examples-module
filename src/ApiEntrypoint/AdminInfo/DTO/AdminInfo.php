<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\DTO;

readonly class AdminInfo implements AdminInfoInterface
{
    public function __construct(
        private string $email,
        private string $greeting,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getGreeting(): string
    {
        return $this->greeting;
    }
}
