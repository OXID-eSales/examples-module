<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace Greeting\Transput;

use OxidEsales\ExamplesModule\Greeting\Transput\AdminGreetingRequest;
use OxidEsales\Eshop\Core\Request as ShopRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AdminGreetingRequestTest extends TestCase
{
    #[Test]
    public function getEditObjectId(): void
    {
        $editObjectId = uniqid();

        $shopRequestMock = $this->createMock(ShopRequest::class);
        $shopRequestMock->method('getRequestParameter')
            ->with('oxid')
            ->willReturn($editObjectId);

        $sut = new AdminGreetingRequest($shopRequestMock);
        $this->assertSame($editObjectId, $sut->getEditObjectId());
    }
}
