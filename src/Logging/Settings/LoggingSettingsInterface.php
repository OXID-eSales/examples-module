<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Logging\Settings;

interface LoggingSettingsInterface
{
    public const LOGGER_STATUS = 'oeexamplesmodule_LoggerEnabled';

    public function isLoggingEnabled(): bool;
}
