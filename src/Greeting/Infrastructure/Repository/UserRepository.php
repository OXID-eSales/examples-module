<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository;

use OxidEsales\ExamplesModule\Extension\Model\User;
use OxidEsales\ExamplesModule\Greeting\Exception\UserNotLoggedIn;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Factory\UserModelFactoryInterface;
use OxidEsales\ExamplesModule\Greeting\Model\PersonalGreetingUserInterface;

readonly class UserRepository implements UserRepositoryInterface
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

    public function getActiveUser(): User
    {
        $userModel = $this->userModelFactory->create();
        $loadingSuccessful = $userModel->loadActiveUser();

        if (!$loadingSuccessful) {
            throw new UserNotLoggedIn();
        }

        return $userModel;
    }
}
