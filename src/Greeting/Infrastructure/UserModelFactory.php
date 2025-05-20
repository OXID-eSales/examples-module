<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Infrastructure;

use OxidEsales\Eshop\Application\Model\User;

class UserModelFactory implements UserModelFactoryInterface
{
    /**
     * @return \OxidEsales\ExamplesModule\Extension\Model\User
     */
    public function create(): User
    {
        /** @phpstan-ignore return.type */
        return oxNew(User::class);
    }
}
