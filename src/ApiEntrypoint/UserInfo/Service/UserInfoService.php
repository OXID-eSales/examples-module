<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Service;

use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Infrastructure\UserRepositoryInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\DTO\UserInfo;
use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\DTO\UserInfoInterface;

readonly class UserInfoService implements UserInfoServiceInterface
{
    private const GREETING_CONTROLLER_URL = 'index.php?cl=oeem_greeting';

    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function getUserInfo(string $username): ?UserInfoInterface
    {
        $firstName = $this->userRepository->getFirstNameByUsername($username);

        if ($firstName === null) {
            return null;
        }

        return new UserInfo(
            firstName: $firstName,
            greetingUrl: self::GREETING_CONTROLLER_URL,
        );
    }
}
