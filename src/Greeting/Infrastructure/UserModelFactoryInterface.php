<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\ExamplesModule\Greeting\Infrastructure;

use OxidEsales\Eshop\Application\Model\User;

interface UserModelFactoryInterface
{
    /**
     * @return \OxidEsales\ExamplesModule\Extension\Model\User
     */
    public function create(): User;
}
