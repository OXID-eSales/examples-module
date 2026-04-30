<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Service;

interface ProductInfoServiceInterface
{
    public function getActiveProductCount(): int;

    public function getGreetingMessage(): string;
}
