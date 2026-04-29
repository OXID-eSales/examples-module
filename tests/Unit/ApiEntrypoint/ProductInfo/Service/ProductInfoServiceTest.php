<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\ProductInfo\Service;

use OxidEsales\EshopCommunity\Internal\Transition\Adapter\ShopAdapterInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Dao\ActiveProductCountDaoInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Service\ProductInfoService;
use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ProductInfoService::class)]
final class ProductInfoServiceTest extends TestCase
{
    public function testGetActiveProductCountDelegatesToDao(): void
    {
        $expectedCount = mt_rand(0, 3);

        $daoStub = $this->createStub(ActiveProductCountDaoInterface::class);
        $daoStub->method('getActiveProductCount')
            ->willReturn($expectedCount);

        $sut = $this->getSut(productCountDao: $daoStub);

        $this->assertSame($expectedCount, $sut->getActiveProductCount());
    }

    public function testGetGreetingMessageTranslatesLanguageConstant(): void
    {
        $expectedTranslation = uniqid('translation_');

        $shopAdapterMock = $this->createStub(ShopAdapterInterface::class);
        $shopAdapterMock->method('translateString')
            ->with(ModuleCore::API_HELLO_LANGUAGE_CONST)
            ->willReturn($expectedTranslation);

        $sut = $this->getSut(shopAdapter: $shopAdapterMock);

        $this->assertSame($expectedTranslation, $sut->getGreetingMessage());
    }

    private function getSut(
        ?ActiveProductCountDaoInterface $productCountDao = null,
        ?ShopAdapterInterface $shopAdapter = null,
    ): ProductInfoService {
        $productCountDao ??= $this->createStub(ActiveProductCountDaoInterface::class);
        $shopAdapter ??= $this->createStub(ShopAdapterInterface::class);

        return new ProductInfoService(
            productCountDao: $productCountDao,
            shopAdapter: $shopAdapter,
        );
    }
}
