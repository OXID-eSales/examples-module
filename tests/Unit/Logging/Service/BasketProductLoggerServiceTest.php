<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Logging\Service;

use OxidEsales\ExamplesModule\Logging\Service\BasketProductLoggerService;
use OxidEsales\ExamplesModule\Logging\Settings\LoggingSettingsInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

#[CoversClass(BasketProductLoggerService::class)]
final class BasketProductLoggerServiceTest extends TestCase
{
    private const TEST_PRODUCT_ID = 'itemId';

    public function testLogWhenEnabled(): void
    {
        $psrLoggerMock = $this->createMock(PsrLoggerInterface::class);
        $psrLoggerMock->expects($this->once())
            ->method('info')
            ->with(
                sprintf(BasketProductLoggerService::MESSAGE, self::TEST_PRODUCT_ID)
            );

        $loggingSettingsStub = $this->createStub(LoggingSettingsInterface::class);
        $loggingSettingsStub->method('isLoggingEnabled')->willReturn(true);

        $sut = $this->getSut(
            logger: $psrLoggerMock,
            loggingSettings: $loggingSettingsStub
        );

        $sut->log(self::TEST_PRODUCT_ID);
    }

    public function testLogWhenDisabled(): void
    {
        $psrLoggerMock = $this->createMock(PsrLoggerInterface::class);
        $psrLoggerMock->expects($this->never())
            ->method('info');

        $loggingSettingsStub = $this->createConfiguredStub(LoggingSettingsInterface::class, [
            'isLoggingEnabled' => false,
        ]);

        $sut = $this->getSut(
            logger: $psrLoggerMock,
            loggingSettings: $loggingSettingsStub
        );

        $sut->log(self::TEST_PRODUCT_ID);
    }

    private function getSut(
        ?PsrLoggerInterface $logger = null,
        ?LoggingSettingsInterface $loggingSettings = null,
    ): BasketProductLoggerService {
        $logger ??= $this->createStub(PsrLoggerInterface::class);
        $loggingSettings ??= $this->createStub(LoggingSettingsInterface::class);

        return new BasketProductLoggerService(
            logger: $logger,
            loggingSettings: $loggingSettings
        );
    }
}
