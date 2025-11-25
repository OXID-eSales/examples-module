<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Logging\Service;

use OxidEsales\ExamplesModule\Logging\Settings\LoggingSettingsInterface;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * Class logs items which goes to basket.
 */
readonly class BasketProductLoggerService implements BasketProductLoggerServiceInterface
{
    public const MESSAGE = 'Adding item with id \'%s\'.';

    public function __construct(
        private PsrLoggerInterface $logger,
        private LoggingSettingsInterface $loggingSettings,
    ) {
    }

    public function log(string $productID): void
    {
        if ($this->loggingSettings->isLoggingEnabled()) {
            $message = sprintf(self::MESSAGE, $productID);
            $this->logger->info($message);
        }
    }
}
