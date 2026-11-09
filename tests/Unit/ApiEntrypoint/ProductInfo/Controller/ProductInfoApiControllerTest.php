<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\ProductInfo\Controller;

use OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Controller\ProductInfoApiController;
use OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Service\ProductInfoServiceInterface;
use OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\ApiEntrypointTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ProductInfoApiControllerTest extends ApiEntrypointTestCase
{
    #[Test]
    public function getProductInfoReturnsJsonResponseWithStatus200(): void
    {
        $sut = $this->getSut();
        $response = $sut->getProductInfo();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    #[Test]
    public function getProductInfoContainsProductCountAndMessage(): void
    {
        $expectedCount = mt_rand(1, 10000);
        $expectedMessage = uniqid('message_');
        $serviceStub = $this->createConfiguredStub(ProductInfoServiceInterface::class, [
            'getActiveProductCount' => $expectedCount,
            'getGreetingMessage' => $expectedMessage,
        ]);

        $sut = $this->getSut(
            productInfoService: $serviceStub,
        );
        $data = $this->decodeResponse($sut->getProductInfo());

        $this->assertSame($expectedCount, $data['productCount']);
        $this->assertSame($expectedMessage, $data['message']);
    }

    private function getSut(
        ?ProductInfoServiceInterface $productInfoService = null,
    ): ProductInfoApiController {
        $productInfoService ??= $this->createStub(ProductInfoServiceInterface::class);

        return new ProductInfoApiController(
            productInfoService: $productInfoService,
        );
    }
}
