<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository;

use OxidEsales\ExamplesModule\Greeting\Model\PersonalGreetingUserInterface;

interface UserRepositoryInterface
{
    public function getUserById(string $userId): PersonalGreetingUserInterface;
}
