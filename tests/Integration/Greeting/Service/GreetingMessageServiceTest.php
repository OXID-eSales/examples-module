<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Greeting\Service;

use OxidEsales\EshopCommunity\Internal\Transition\Adapter\ShopAdapterInterface;
use OxidEsales\ExamplesModule\Extension\Model\User;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\GreetingRepositoryInterface;
use OxidEsales\ExamplesModule\Greeting\Service\GreetingMessageService;
use OxidEsales\ExamplesModule\Greeting\Settings\GreetingSettingsInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(GreetingMessageService::class)]
/**
 * @todo: whole strategy could be reworked so the greeting would come from repository BY the user,
 *        but not FROM/THROUGH the user, then User extension will not be needed at all
 */
final class GreetingMessageServiceTest extends TestCase
{
    public function testGenericGreetingWithUserForPersonalMode(): void
    {
        $sut = $this->getSut(
            greetingSettings: $greetingSettingsStub = $this->createStub(GreetingSettingsInterface::class),
            shopAdapter: $shopAdapterMock = $this->createMock(ShopAdapterInterface::class),
        );

        $greetingSettingsStub->method('getGreetingMode')
            ->willReturn(GreetingSettingsInterface::GREETING_MODE_PERSONAL);

        $personalGreeting = 'someUserPersonalGreeting';
        /** @var User $userStub */
        $userStub = $this->createStub(User::class);
        $userStub->method('getPersonalGreeting')->willReturn($personalGreeting);

        $expectedTranslation = 'translatedGreeting';
        $shopAdapterMock->expects($this->once())
            ->method('translateString')
            ->with($personalGreeting)
            ->willReturn($expectedTranslation);

        $this->assertSame($expectedTranslation, $sut->getGreeting($userStub));
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
