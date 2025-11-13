<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Service;

use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\UserRepositoryInterface;
use OxidEsales\ExamplesModule\Greeting\Model\PersonalGreetingUserInterface;

/**
 * @extendable-class
 */
readonly class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function getUserById(string $userId): PersonalGreetingUserInterface
    {
        return $this->userRepository->getUserById($userId);
    }
}
