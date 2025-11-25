<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Model;

use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\GreetingRepository;

/** @phpstan-require-extends BaseModel */
trait PersonalGreetingUser
{
    public function getPersonalGreeting(): string
    {
        return (string)$this->getRawFieldData(GreetingRepository::OEEM_USER_GREETING_FIELD);
    }
}
