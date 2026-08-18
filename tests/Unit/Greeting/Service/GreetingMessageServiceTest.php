<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Greeting\Service;

use OxidEsales\EshopCommunity\Internal\Transition\Adapter\ShopAdapterInterface;
use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use OxidEsales\ExamplesModule\Greeting\Exception\UserNotLoggedIn;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\GreetingRepositoryInterface;
use OxidEsales\ExamplesModule\Greeting\Service\GreetingMessageService;
use OxidEsales\ExamplesModule\Greeting\Settings\GreetingSettingsInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(GreetingMessageService::class)]
final class GreetingMessageServiceTest extends TestCase
{
    public function testGeneralGreeting(): void
    {
        $shopName = uniqid();
        $service = $this->getSut(
            shopAdapter: $shopAdapterMock = $this->createMock(ShopAdapterInterface::class),
            shopName: $shopName,
        );

        $translatedString = uniqid() . ' %s';
        $shopAdapterMock->expects($this->once())
            ->method('translateString')
            ->with(ModuleCore::GENERAL_GREETING_LANGUAGE_CONST)
            ->willReturn($translatedString);

        $this->assertSame(sprintf($translatedString, $shopName), $service->getGeneralGreeting());
    }

    public function testGenericGreetingNoUserForGenericMode(): void
    {
        $service = $this->getSut(
            greetingSettings: $greetingSettingsStub = $this->createStub(GreetingSettingsInterface::class),
            shopAdapter: $shopAdapterMock = $this->createMock(ShopAdapterInterface::class),
        );

        $greetingSettingsStub->method('getGreetingMode')
            ->willReturn(GreetingSettingsInterface::GREETING_MODE_GENERIC);

        $expectedTranslation = 'translatedGreeting';
        $shopAdapterMock->expects($this->once())
            ->method('translateString')
            ->with(ModuleCore::DEFAULT_PERSONAL_GREETING_LANGUAGE_CONST)
            ->willReturn($expectedTranslation);

        $this->assertSame($expectedTranslation, $service->getGreeting(null));
    }

    public function testGenericGreetingWithUserForGenericMode(): void
    {
        $service = $this->getSut(
            greetingSettings: $greetingSettingsStub = $this->createStub(GreetingSettingsInterface::class),
            shopAdapter: $shopAdapterMock = $this->createMock(ShopAdapterInterface::class),
        );

        $greetingSettingsStub->method('getGreetingMode')
            ->willReturn(GreetingSettingsInterface::GREETING_MODE_GENERIC);

        $expectedTranslation = 'translatedGreeting';
        $shopAdapterMock->expects($this->once())
            ->method('translateString')
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

    #[Test]
    public function greetingSaveTriggersGreetingSaveInRepository(): void
    {
        $exampleGreeting = uniqid('greeting_');

        $greetingRepositorySpy = $this->createMock(GreetingRepositoryInterface::class);
        $greetingRepositorySpy->expects($this->once())
            ->method('saveGreetingForActiveUser')
            ->with($exampleGreeting);

        $sut = $this->getSut(
            greetingRepository: $greetingRepositorySpy,
        );

        $sut->saveGreetingForCurrentUser($exampleGreeting);
    }

    #[Test]
    public function greetingSaveInRepositoryExplosionCatched(): void
    {
        $exampleGreeting = uniqid('greeting_');

        $greetingRepositorySpy = $this->createMock(GreetingRepositoryInterface::class);
        $greetingRepositorySpy->expects($this->once())
            ->method('saveGreetingForActiveUser')
            ->willThrowException(new UserNotLoggedIn());

        $sut = $this->getSut(
            greetingRepository: $greetingRepositorySpy,
        );

        $sut->saveGreetingForCurrentUser($exampleGreeting);
    }

    private function getSut(
        ?GreetingSettingsInterface $greetingSettings = null,
        ?ShopAdapterInterface $shopAdapter = null,
        ?string $shopName = null,
        ?GreetingRepositoryInterface $greetingRepository = null,
    ): GreetingMessageService {
        return new GreetingMessageService(
            greetingSettings: $greetingSettings ?? $this->createStub(GreetingSettingsInterface::class),
            shopAdapter: $shopAdapter ?? $this->createStub(ShopAdapterInterface::class),
            shopName: $shopName ?? uniqid(),
            greetingRepository: $greetingRepository ?? $this->createStub(GreetingRepositoryInterface::class),
        );
    }
}
