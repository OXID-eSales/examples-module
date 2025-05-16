<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\ExamplesModule\Greeting\Transput;

interface RequestInterface
{
    public function getEditObjectId(): string;
}
