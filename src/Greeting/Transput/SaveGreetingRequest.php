<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Transput;

use OxidEsales\EshopCommunity\Internal\Framework\Request\RequestInterface;

class SaveGreetingRequest implements SaveGreetingRequestInterface
{
    public const OEEM_GREETING_FORM_FIELD = 'oeem_greeting';

    public function __construct(
        private readonly RequestInterface $request,
    ) {
    }

    public function getGreetingMessage(): string
    {
        return (string)$this->request->get(self::OEEM_GREETING_FORM_FIELD, '');
    }
}
