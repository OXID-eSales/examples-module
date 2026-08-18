<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\AdminInfo\Service;

use OxidEsales\EshopCommunity\Internal\Transition\Adapter\ShopAdapterInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\Service\AdminInfoService;
use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AdminInfoService::class)]
final class AdminInfoServiceTest extends TestCase
{
    public function testGetAdminInfoReturnsEmail(): void
    {
        $username = uniqid('admin_') . '@example.com';

        $sut = $this->getSut();

        $this->assertSame($username, $sut->getAdminInfo($username)->getEmail());
    }

    public function testGetAdminInfoReturnsTranslatedGreetingWithEmail(): void
    {
        $username = uniqid('admin_') . '@example.com';
        $translatedPattern = uniqid('hello_') . ' %s';

        $shopAdapterMock = $this->createMock(ShopAdapterInterface::class);
        $shopAdapterMock->expects($this->once())
            ->method('translateString')
            ->with(ModuleCore::ADMIN_HELLO_LANGUAGE_CONST)
            ->willReturn($translatedPattern);

        $sut = $this->getSut(shopAdapter: $shopAdapterMock);

        $this->assertSame(
            sprintf($translatedPattern, $username),
            $sut->getAdminInfo($username)->getGreeting()
        );
    }

    private function getSut(
        ?ShopAdapterInterface $shopAdapter = null,
    ): AdminInfoService {
        $shopAdapter ??= $this->createStub(ShopAdapterInterface::class);

        return new AdminInfoService(
            shopAdapter: $shopAdapter,
        );
    }
}
