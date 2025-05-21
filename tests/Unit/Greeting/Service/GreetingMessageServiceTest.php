<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Greeting\Service;

use OxidEsales\Eshop\Core\Language as CoreLanguage;
use OxidEsales\Eshop\Core\Request as CoreRequest;
use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use OxidEsales\ExamplesModule\Greeting\Service\GreetingMessageService;
use OxidEsales\ExamplesModule\Settings\Service\ModuleSettingsServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(GreetingMessageService::class)]
final class GreetingMessageServiceTest extends TestCase
{
    public function testGeneralGreeting(): void
    {
        $shopName = uniqid();
        $service = $this->getGreetingMessageService(
            shopLanguage: $langStub = $this->createStub(CoreLanguage::class),
            shopName: $shopName,
        );

        $translatedString = 'Welcome to shop %s!';
        $langStub->method('translateString')
            ->with(ModuleCore::GENERAL_GREETING_LANGUAGE_CONST)
            ->willReturn($translatedString);

        $this->assertSame(sprintf($translatedString, $shopName), $service->getGeneralGreeting());
    }

    public function testGenericGreetingNoUserForGenericMode(): void
    {
        $service = $this->getGreetingMessageService(
            moduleSettings: $moduleSettingsStub = $this->createMock(ModuleSettingsServiceInterface::class),
            shopLanguage: $langStub = $this->createStub(CoreLanguage::class),
        );

        $moduleSettingsStub->method('getGreetingMode')
            ->willReturn(ModuleSettingsServiceInterface::GREETING_MODE_GENERIC);

        $expectedTranslation = 'translatedGreeting';
        $langStub->method('translateString')
            ->with(ModuleCore::DEFAULT_PERSONAL_GREETING_LANGUAGE_CONST)
            ->willReturn($expectedTranslation);

        $this->assertSame($expectedTranslation, $service->getGreeting(null));
    }

    public function testGenericGreetingWithUserForGenericMode(): void
    {
        $service = $this->getGreetingMessageService(
            moduleSettings: $moduleSettingsStub = $this->createMock(ModuleSettingsServiceInterface::class),
            shopLanguage: $langStub = $this->createStub(CoreLanguage::class),
        );

        $moduleSettingsStub->method('getGreetingMode')
            ->willReturn(ModuleSettingsServiceInterface::GREETING_MODE_GENERIC);

        $expectedTranslation = 'translatedGreeting';
        $langStub->method('translateString')
            ->with(ModuleCore::DEFAULT_PERSONAL_GREETING_LANGUAGE_CONST)
            ->willReturn($expectedTranslation);

        $this->assertSame($expectedTranslation, $service->getGreeting(null));
    }

    public function testGenericGreetingNoUserForPersonalMode(): void
    {
        $service = $this->getGreetingMessageService(
            moduleSettings: $moduleSettingsStub = $this->createMock(ModuleSettingsServiceInterface::class),
        );

        $moduleSettingsStub->method('getGreetingMode')
            ->willReturn(ModuleSettingsServiceInterface::GREETING_MODE_PERSONAL);

        $this->assertSame('', $service->getGreeting(null));
    }

    private function getGreetingMessageService(
        ?ModuleSettingsServiceInterface $moduleSettings = null,
        ?CoreRequest $shopRequest = null,
        ?CoreLanguage $shopLanguage = null,
        ?string $shopName = null,
    ): GreetingMessageService {
        return new GreetingMessageService(
            moduleSettings: $moduleSettings ?? $this->createStub(ModuleSettingsServiceInterface::class),
            shopRequest: $shopRequest ?? $this->createStub(CoreRequest::class),
            shopLanguage: $shopLanguage ?? $this->createStub(CoreLanguage::class),
            shopName: $shopName ?? 'Test Shop',
        );
    }
}
