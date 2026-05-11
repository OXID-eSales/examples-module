<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\ExamplesModule\Greeting\Twig\Extension;

interface ExampleLogicInterface
{
    public function exampleMethod(string $methodParam): string;
}
