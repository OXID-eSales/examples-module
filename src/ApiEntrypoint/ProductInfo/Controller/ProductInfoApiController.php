<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Controller;

use OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Service\ProductInfoServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

readonly class ProductInfoApiController
{
    public function __construct(
        private ProductInfoServiceInterface $productInfoService,
    ) {
    }

    #[Route('/api/product-info', methods: ['GET'])]
    public function getProductInfo(): JsonResponse
    {
        return new JsonResponse([
            'productCount' => $this->productInfoService->getActiveProductCount(),
            'message' => $this->productInfoService->getGreetingMessage(),
        ]);
    }
}
