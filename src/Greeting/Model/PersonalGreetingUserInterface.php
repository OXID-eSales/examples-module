<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Model;

interface PersonalGreetingUserInterface
{
    public const OEEM_USER_GREETING_FIELD = 'oeemgreeting';

    public function getPersonalGreeting(): string;

    public function setPersonalGreeting(string $personalGreeting): void;
}
