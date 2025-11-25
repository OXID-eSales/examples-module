<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\ExamplesModule\Tests\Unit\Greeting\Transput;

use OxidEsales\EshopCommunity\Internal\Framework\Request\RequestInterface;
use OxidEsales\ExamplesModule\Greeting\Transput\AdminGreetingRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AdminGreetingRequestTest extends TestCase
{
    #[Test]
    public function getEditObjectId(): void
    {
        $editObjectId = uniqid();

        $shopRequestMock = $this->createMock(RequestInterface::class);
        $shopRequestMock->method('get')
            ->with('oxid')
            ->willReturn($editObjectId);

        $sut = new AdminGreetingRequest($shopRequestMock);
        $this->assertSame($editObjectId, $sut->getEditObjectId());
    }
}
