<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Service;

use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\DTO\UserInfoInterface;

interface UserInfoServiceInterface
{
    public function getUserInfo(string $username): ?UserInfoInterface;
}
