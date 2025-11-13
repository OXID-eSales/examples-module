<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Settings;

interface GreetingSettingsInterface
{
    public const GREETING_MODE = 'oeexamplesmodule_GreetingMode';

    public const GREETING_MODE_GENERIC = 'generic';

    public const GREETING_MODE_PERSONAL = 'personal';

    public function isPersonalGreetingMode(): bool;

    public function getGreetingMode(): string;

    public function saveGreetingMode(string $value): void;
}
