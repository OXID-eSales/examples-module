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
    public function testGetProductInfoReturnsJsonResponse(): void
    {
        $sut = $this->getSut();

        $this->assertInstanceOf(JsonResponse::class, $sut->getProductInfo());
    }

    public function testGetProductInfoReturnsStatus200(): void
    {
        $sut = $this->getSut();

        $this->assertSame(200, $sut->getProductInfo()->getStatusCode());
    }

    public function testGetProductInfoContainsProductCount(): void
    {
        $expectedCount = mt_rand(1, 10000);

        $serviceStub = $this->createStub(ProductInfoServiceInterface::class);
        $serviceStub->method('getActiveProductCount')
            ->willReturn($expectedCount);

        $sut = $this->getSut(productInfoService: $serviceStub);

        $data = $this->decodeResponse($sut->getProductInfo());

        $this->assertSame($expectedCount, $data['productCount']);
    }

    public function testGetProductInfoContainsTranslatedMessage(): void
    {
        $expectedMessage = uniqid('message_', true);

        $serviceStub = $this->createStub(ProductInfoServiceInterface::class);
        $serviceStub->method('getGreetingMessage')
            ->willReturn($expectedMessage);

        $sut = $this->getSut(productInfoService: $serviceStub);

        $data = $this->decodeResponse($sut->getProductInfo());

        $this->assertSame($expectedMessage, $data['message']);
    }

    public function testGetProductInfoResponseStructure(): void
    {
        $sut = $this->getSut();

        $data = $this->decodeResponse($sut->getProductInfo());

        $this->assertArrayHasKey('productCount', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertCount(2, $data);
    }

    private function getSut(
        ?ProductInfoServiceInterface $productInfoService = null,
    ): ProductInfoApiController {
        return new ProductInfoApiController(
            productInfoService: $productInfoService
                ?? $this->createStub(ProductInfoServiceInterface::class),
        );
    }

    private function decodeResponse(JsonResponse $response): array
    {
        return json_decode($response->getContent(), true);
    }
}
