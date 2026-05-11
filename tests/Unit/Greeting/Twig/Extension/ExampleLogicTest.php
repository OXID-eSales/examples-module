<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Greeting\Twig\Extension;

use OxidEsales\ExamplesModule\Greeting\Twig\Extension\ExampleLogic;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ExampleLogic::class)]
final class ExampleLogicTest extends TestCase
{
    public function testExampleMethodIncludesParam(): void
    {
        $param = uniqid('param_');

        $sut = new ExampleLogic();
        $result = $sut->exampleMethod($param);

        $this->assertStringContainsString($param, $result);
    }
}
