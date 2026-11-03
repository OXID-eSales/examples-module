<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\ProductInfo\Service;

use OxidEsales\EshopCommunity\Internal\Transition\Adapter\ShopAdapterInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Infrastructure\ProductRepositoryInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Service\ProductInfoService;
use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ProductInfoServiceTest extends TestCase
{
    #[Test]
    public function getActiveProductCountDelegatesToRepository(): void
    {
        $expectedCount = mt_rand(0, 3);

        $repositoryStub = $this->createStub(ProductRepositoryInterface::class);
        $repositoryStub->method('getActiveProductCount')
            ->willReturn($expectedCount);

        $sut = $this->getSut(
            productRepository: $repositoryStub,
        );

        $this->assertSame($expectedCount, $sut->getActiveProductCount());
    }

    #[Test]
    public function getGreetingMessageTranslatesLanguageConstant(): void
    {
        $expectedTranslation = uniqid('translation_');

        $shopAdapterMock = $this->createMock(ShopAdapterInterface::class);
        $shopAdapterMock->expects($this->once())
            ->method('translateString')
            ->with(ModuleCore::API_HELLO_LANGUAGE_CONST)
            ->willReturn($expectedTranslation);

        $sut = $this->getSut(
            shopAdapter: $shopAdapterMock,
        );

        $this->assertSame($expectedTranslation, $sut->getGreetingMessage());
    }

    private function getSut(
        ?ProductRepositoryInterface $productRepository = null,
        ?ShopAdapterInterface $shopAdapter = null,
    ): ProductInfoService {
        $productRepository ??= $this->createStub(ProductRepositoryInterface::class);
        $shopAdapter ??= $this->createStub(ShopAdapterInterface::class);

        return new ProductInfoService(
            productRepository: $productRepository,
            shopAdapter: $shopAdapter,
        );
    }
}
