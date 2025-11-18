<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Transput;

use OxidEsales\EshopCommunity\Internal\Framework\Request\RequestInterface;

class AdminGreetingRequest implements AdminGreetingRequestInterface
{
    public function __construct(
        protected RequestInterface $request
    ) {
    }

    public function getEditObjectId(): string
    {
        return (string) $this->request->get('oxid');
    }
}
