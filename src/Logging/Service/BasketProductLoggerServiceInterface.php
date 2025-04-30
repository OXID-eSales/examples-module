<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Logging\Service;

interface BasketProductLoggerServiceInterface
{
    public function log(string $productID): void;
}
