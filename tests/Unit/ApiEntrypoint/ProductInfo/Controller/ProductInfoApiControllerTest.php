<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\ProductInfo\Controller;

use OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Controller\ProductInfoApiController;
use OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Service\ProductInfoServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

#[CoversClass(ProductInfoApiController::class)]
final class ProductInfoApiControllerTest extends TestCase
{
    public function testGetProductInfoReturnsJsonResponseWithStatus200(): void
    {
        $sut = $this->getSut();
        $response = $sut->getProductInfo();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testGetProductInfoContainsProductCountAndMessage(): void
    {
        $expectedCount = mt_rand(1, 10000);
        $expectedMessage = uniqid('message_');
        $serviceStub = $this->createConfiguredStub(ProductInfoServiceInterface::class, [
            'getActiveProductCount' => $expectedCount,
            'getGreetingMessage' => $expectedMessage,
        ]);

        $sut = $this->getSut(productInfoService: $serviceStub);
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

    private function decodeResponse(JsonResponse $response): array
    {
        return json_decode($response->getContent(), true);
    }
}
