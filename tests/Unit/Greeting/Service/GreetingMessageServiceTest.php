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
use OxidEsales\ExamplesModule\Greeting\Settings\GreetingSettingsInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(GreetingMessageService::class)]
final class GreetingMessageServiceTest extends TestCase
{
    public function testGeneralGreeting(): void
    {
        $shopName = uniqid();
        $service = $this->getSut(
            shopLanguage: $langStub = $this->createStub(CoreLanguage::class),
            shopName: $shopName,
        );

        $translatedString = uniqid() . ' %s';
        $langStub->method('translateString')
            ->with(ModuleCore::GENERAL_GREETING_LANGUAGE_CONST)
            ->willReturn($translatedString);

        $this->assertSame(sprintf($translatedString, $shopName), $service->getGeneralGreeting());
    }

    public function testGenericGreetingNoUserForGenericMode(): void
    {
        $service = $this->getSut(
            greetingSettings: $greetingSettingsStub = $this->createStub(GreetingSettingsInterface::class),
            shopLanguage: $langStub = $this->createStub(CoreLanguage::class),
        );

        $greetingSettingsStub->method('getGreetingMode')
            ->willReturn(GreetingSettingsInterface::GREETING_MODE_GENERIC);

        $expectedTranslation = 'translatedGreeting';
        $langStub->method('translateString')
            ->with(ModuleCore::DEFAULT_PERSONAL_GREETING_LANGUAGE_CONST)
            ->willReturn($expectedTranslation);

        $this->assertSame($expectedTranslation, $service->getGreeting(null));
    }

    public function testGenericGreetingWithUserForGenericMode(): void
    {
        $service = $this->getSut(
            greetingSettings: $greetingSettingsStub = $this->createStub(GreetingSettingsInterface::class),
            shopLanguage: $langStub = $this->createStub(CoreLanguage::class),
        );

        $greetingSettingsStub->method('getGreetingMode')
            ->willReturn(GreetingSettingsInterface::GREETING_MODE_GENERIC);

        $expectedTranslation = 'translatedGreeting';
        $langStub->method('translateString')
            ->with(ModuleCore::DEFAULT_PERSONAL_GREETING_LANGUAGE_CONST)
            ->willReturn($expectedTranslation);

        $this->assertSame($expectedTranslation, $service->getGreeting(null));
    }

    public function testGenericGreetingNoUserForPersonalMode(): void
    {
        $service = $this->getSut(
            greetingSettings: $greetingSettingsStub = $this->createStub(GreetingSettingsInterface::class),
        );

        $greetingSettingsStub->method('getGreetingMode')
            ->willReturn(GreetingSettingsInterface::GREETING_MODE_PERSONAL);

        $this->assertSame('', $service->getGreeting(null));
    }

    private function getSut(
        ?GreetingSettingsInterface $greetingSettings = null,
        ?CoreRequest $shopRequest = null,
        ?CoreLanguage $shopLanguage = null,
        ?string $shopName = null,
    ): GreetingMessageService {
        return new GreetingMessageService(
            greetingSettings: $greetingSettings ?? $this->createStub(GreetingSettingsInterface::class),
            shopRequest: $shopRequest ?? $this->createStub(CoreRequest::class),
            shopLanguage: $shopLanguage ?? $this->createStub(CoreLanguage::class),
            shopName: $shopName ?? uniqid(),
        );
    }
}
