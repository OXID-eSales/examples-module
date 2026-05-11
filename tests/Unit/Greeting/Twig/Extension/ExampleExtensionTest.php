<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Greeting\Twig\Extension;

use OxidEsales\ExamplesModule\Greeting\Twig\Extension\ExampleExtension;
use OxidEsales\ExamplesModule\Greeting\Twig\Extension\ExampleLogicInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Twig\TwigFunction;

#[CoversClass(ExampleExtension::class)]
final class ExampleExtensionTest extends TestCase
{
    public function testGetFunctionsRegistersExampleFunction(): void
    {
        $logicStub = $this->createStub(ExampleLogicInterface::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->willReturnMap([
                [ExampleLogicInterface::class, $logicStub],
            ]);

        $sut = new ExampleExtension(container: $container);
        $functions = $sut->getFunctions();

        $exampleFunction = current(array_filter(
            $functions,
            fn(TwigFunction $function) => $function->getName() === 'example'
        ));
        $callable = $exampleFunction->getCallable();

        $this->assertInstanceOf(TwigFunction::class, $exampleFunction);
        $this->assertSame($logicStub, $callable[0]);
        $this->assertSame('exampleMethod', $callable[1]);
    }
}
