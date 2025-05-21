<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Service;

use OxidEsales\ExamplesModule\Greeting\Infrastructure\UserModelFactoryInterface;
use OxidEsales\ExamplesModule\Greeting\Model\PersonalGreetingUserInterface;

/**
 * @extendable-class
 *
 * @todo: getting the user should go through the user repository
 */
readonly class UserService implements UserServiceInterface
{
    public function __construct(
        private UserModelFactoryInterface $userModelFactory,
    ) {
    }

    public function getUserById(string $userId): PersonalGreetingUserInterface
    {
        $userModel = $this->userModelFactory->create();
        $userModel->load($userId);

        return $userModel;
    }
}
