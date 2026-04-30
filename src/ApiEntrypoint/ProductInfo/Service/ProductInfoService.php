<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Service;

use OxidEsales\EshopCommunity\Internal\Transition\Adapter\ShopAdapterInterface;
use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Infrastructure\ProductRepositoryInterface;

readonly class ProductInfoService implements ProductInfoServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ShopAdapterInterface $shopAdapter,
    ) {
    }

    public function getActiveProductCount(): int
    {
        return $this->productRepository->getActiveProductCount();
    }

    public function getGreetingMessage(): string
    {
        return $this->shopAdapter->translateString(
            ModuleCore::API_HELLO_LANGUAGE_CONST
        );
    }
}
