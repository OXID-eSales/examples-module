<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Transput;

class AdminGreetingRequest implements AdminGreetingRequestInterface
{
    public function __construct(
        protected \OxidEsales\Eshop\Core\Request $request
    ) {
    }

    public function getEditObjectId(): string
    {
        return (string) $this->request->getRequestParameter('oxid');
    }
}
