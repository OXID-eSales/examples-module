<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Greeting\Twig\Extension;

use OxidEsales\ExamplesModule\Greeting\Twig\Extension\ExampleLogic;
use OxidEsales\ExamplesModule\Greeting\Twig\Extension\ExampleLogicInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ExampleLogicTest extends TestCase
{
    #[Test]
    public function exampleMethodIncludesParam(): void
    {
        $param = uniqid('param_');

        $sut = $this->getSut();
        $result = $sut->exampleMethod($param);

        $this->assertStringContainsString($param, $result);
    }

    private function getSut(): ExampleLogicInterface
    {
        return new ExampleLogic();
    }
}
