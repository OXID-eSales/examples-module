<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace Greeting\Transput;

use OxidEsales\ExamplesModule\Greeting\Transput\Request;
use OxidEsales\Eshop\Core\Request as ShopRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{
    #[Test]
    public function getEditObjectId(): void
    {
        $editObjectId = uniqid();

        $shopRequestMock = $this->createMock(ShopRequest::class);
        $shopRequestMock->method('getRequestParameter')
            ->with('oxid')
            ->willReturn($editObjectId);

        $sut = new Request($shopRequestMock);
        $this->assertSame($editObjectId, $sut->getEditObjectId());
    }
}
