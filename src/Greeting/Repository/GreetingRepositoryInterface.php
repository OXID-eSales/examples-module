<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Repository;

interface GreetingRepositoryInterface
{
    public function getSavedUserGreeting(string $userId): string;
}
