<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Greeting\Settings;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\ExamplesModule\Core\Module;
use OxidEsales\ExamplesModule\Greeting\Settings\GreetingSettings;
use OxidEsales\ExamplesModule\Greeting\Settings\GreetingSettingsInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\UnicodeString;

#[CoversClass(GreetingSettings::class)]
final class GreetingSettingsTest extends TestCase
{
    #[DataProvider('getGreetingModeDataProvider')]
    public function testGetGreetingMode(string $value, string $expected): void
    {
        $moduleSettingServiceStub = $this->createStub(ModuleSettingServiceInterface::class);
        $moduleSettingServiceStub->method('getString')->willReturnMap([
            [GreetingSettings::GREETING_MODE, Module::MODULE_ID, new UnicodeString($value)]
        ]);

        $sut = $this->getSut(moduleSettingService: $moduleSettingServiceStub);
        $this->assertSame($expected, $sut->getGreetingMode());
    }

    public static function getGreetingModeDataProvider(): array
    {
        return [
            [
                'value' => '',
                'expected' => GreetingSettings::GREETING_MODE_GENERIC
            ],
            [
                'value' => 'someUnpredictable',
                'expected' => GreetingSettings::GREETING_MODE_GENERIC
            ],
            [
                'value' => GreetingSettings::GREETING_MODE_GENERIC,
                'expected' => GreetingSettings::GREETING_MODE_GENERIC
            ],
            [
                'value' => GreetingSettings::GREETING_MODE_PERSONAL,
                'expected' => GreetingSettings::GREETING_MODE_PERSONAL
            ],
        ];
    }

    #[DataProvider('isPersonalGreetingModeDataProvider')]
    public function testIsPersonalGreetingMode(string $value, bool $expected): void
    {
        $moduleSettingServiceStub = $this->createStub(ModuleSettingServiceInterface::class);
        $moduleSettingServiceStub->method('getString')->willReturnMap([
            [GreetingSettings::GREETING_MODE, Module::MODULE_ID, new UnicodeString($value)]
        ]);

        $sut = $this->getSut(moduleSettingService: $moduleSettingServiceStub);
        $this->assertSame($expected, $sut->isPersonalGreetingMode());
    }

    public static function isPersonalGreetingModeDataProvider(): array
    {
        return [
            [
                'value' => GreetingSettings::GREETING_MODE_GENERIC,
                'expected' => false
            ],
            [
                'value' => GreetingSettings::GREETING_MODE_PERSONAL,
                'expected' => true
            ],
        ];
    }

    public function testSaveGreetingMode(): void
    {
        $value = 'someValue';

        $moduleSettingServiceMock = $this->createMock(ModuleSettingServiceInterface::class);
        $moduleSettingServiceMock->expects($this->atLeastOnce())->method('saveString')->with(
            GreetingSettings::GREETING_MODE,
            $value,
            Module::MODULE_ID
        );

        $sut = $this->getSut(moduleSettingService: $moduleSettingServiceMock);
        $sut->saveGreetingMode($value);
    }

    private function getSut(
        ?ModuleSettingServiceInterface $moduleSettingService = null,
    ): GreetingSettingsInterface {
        $moduleSettingService ??= $this->createStub(ModuleSettingServiceInterface::class);

        return new GreetingSettings(
            moduleSettingService: $moduleSettingService
        );
    }
}
