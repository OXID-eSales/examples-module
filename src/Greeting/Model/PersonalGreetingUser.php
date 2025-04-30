<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Model;

use OxidEsales\Eshop\Core\Model\BaseModel;

/** @phpstan-require-extends BaseModel */
trait PersonalGreetingUser
{
    public function getPersonalGreeting(): string
    {
        return (string)$this->getRawFieldData(PersonalGreetingUserInterface::OEEM_USER_GREETING_FIELD);
    }

    //NOTE: we only assign the value to the model.
    //Calling save() method will then store it in the database
    public function setPersonalGreeting(string $personalGreeting): void
    {
        $this->assign([
            PersonalGreetingUserInterface::OEEM_USER_GREETING_FIELD => $personalGreeting,
        ]);
    }
}
