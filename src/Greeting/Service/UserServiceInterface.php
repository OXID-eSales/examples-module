<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Service;

use OxidEsales\ExamplesModule\Greeting\Model\PersonalGreetingUserInterface;

interface UserServiceInterface
{
    public function getUserById(string $userId): PersonalGreetingUserInterface;
}
