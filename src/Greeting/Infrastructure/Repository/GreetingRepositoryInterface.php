<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository;

use OxidEsales\ExamplesModule\Greeting\Exception\UserNotLoggedIn;

interface GreetingRepositoryInterface
{
    public function getSavedUserGreeting(string $userId): string;

    /**
     * @throws UserNotLoggedIn
     */
    public function saveGreetingForActiveUser(string $greeting): void;
}
