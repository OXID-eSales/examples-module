<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Model;

interface PersonalGreetingUserInterface
{
    public function getPersonalGreeting(): string;

    public function setPersonalGreeting(string $personalGreeting): void;
}
