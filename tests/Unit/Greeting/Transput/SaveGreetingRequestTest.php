<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\ExamplesModule\Tests\Unit\Greeting\Transput;

use OxidEsales\EshopCommunity\Internal\Framework\Request\RequestInterface;
use OxidEsales\ExamplesModule\Greeting\Transput\SaveGreetingRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SaveGreetingRequestTest extends TestCase
{
    #[Test]
    public function getGreetingMessageReturnsCorrectValueFromRequestInterface(): void
    {
        $greetingMessage = uniqid('greetingMessage_');

        $requestMock = $this->createMock(RequestInterface::class);
        $requestMock->method('get')
            ->with(SaveGreetingRequest::OEEM_GREETING_FORM_FIELD, '')
            ->willReturn($greetingMessage);

        $sut = $this->getSut(request: $requestMock);
        $this->assertSame($greetingMessage, $sut->getGreetingMessage());
    }

    private function getSut(
        RequestInterface $request
    ): SaveGreetingRequest {
        return new SaveGreetingRequest(
            request: $request
        );
    }
}
