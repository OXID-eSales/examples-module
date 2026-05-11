<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Twig\Extension;

class ExampleLogic implements ExampleLogicInterface
{
    public function exampleMethod(string $methodParam): string
    {
        return sprintf('Example twig extension function with param: %s', $methodParam);
    }
}
