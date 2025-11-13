<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Logging\Settings;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\ExamplesModule\Core\Module;

/**
 * @extendable-class
 */
readonly class LoggingSettings implements LoggingSettingsInterface
{
    public function __construct(
        private ModuleSettingServiceInterface $moduleSettingService,
    ) {
    }

    public function isLoggingEnabled(): bool
    {
        return $this->moduleSettingService->getBoolean(self::LOGGER_STATUS, Module::MODULE_ID);
    }
}
