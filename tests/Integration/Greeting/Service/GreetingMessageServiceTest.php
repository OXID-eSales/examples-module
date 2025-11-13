<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Greeting\Service;

use OxidEsales\Eshop\Core\Language as CoreLanguage;
use OxidEsales\Eshop\Core\Request as CoreRequest;
use OxidEsales\ExamplesModule\Extension\Model\User;
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
            shopRequest: $this->createStub(CoreRequest::class),
            shopLanguage: $langStub = $this->createStub(CoreLanguage::class),
        );

        $greetingSettingsStub->method('getGreetingMode')
            ->willReturn(GreetingSettingsInterface::GREETING_MODE_PERSONAL);

        $personalGreeting = 'someUserPersonalGreeting';
        /** @var User $userStub */
        $userStub = $this->createStub(User::class);
        $userStub->method('getPersonalGreeting')->willReturn($personalGreeting);

        $expectedTranslation = 'translatedGreeting';
        $langStub->method('translateString')
            ->with($personalGreeting)
            ->willReturn($expectedTranslation);

        $this->assertSame($expectedTranslation, $sut->getGreeting($userStub));
    }

    private function getSut(
        GreetingSettingsInterface $greetingSettings = null,
        CoreRequest $shopRequest = null,
        CoreLanguage $shopLanguage = null,
    ): GreetingMessageService {
        return new GreetingMessageService(
            greetingSettings: $greetingSettings ?? $this->createStub(GreetingSettingsInterface::class),
            shopRequest: $shopRequest ?? $this->createStub(CoreRequest::class),
            shopLanguage: $shopLanguage ?? $this->createStub(CoreLanguage::class),
            shopName: 'Test Shop',
        );
    }
}
