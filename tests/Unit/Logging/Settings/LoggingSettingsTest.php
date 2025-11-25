<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Logging\Settings;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\ExamplesModule\Core\Module;
use OxidEsales\ExamplesModule\Logging\Settings\LoggingSettings;
use OxidEsales\ExamplesModule\Logging\Settings\LoggingSettingsInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LoggingSettings::class)]
final class LoggingSettingsTest extends TestCase
{
    public function testIsLoggingEnabledReturnsExpectedValue(): void
    {
        $expectedValue = (bool)rand(0, 1);

        $moduleSettingServiceStub = $this->createStub(ModuleSettingServiceInterface::class);
        $moduleSettingServiceStub->method('getBoolean')->willReturnMap([
            [LoggingSettings::LOGGER_STATUS, Module::MODULE_ID, $expectedValue]
        ]);

        $sut = $this->getSut(moduleSettingService: $moduleSettingServiceStub);
        $result = $sut->isLoggingEnabled();

        $this->assertSame($expectedValue, $result);
    }

    private function getSut(
        ?ModuleSettingServiceInterface $moduleSettingService = null,
    ): LoggingSettingsInterface {
        $moduleSettingService ??= $this->createStub(ModuleSettingServiceInterface::class);

        return new LoggingSettings(
            moduleSettingService: $moduleSettingService
        );
    }
}
