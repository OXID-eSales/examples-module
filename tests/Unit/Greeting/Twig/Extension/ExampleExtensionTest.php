<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Greeting\Twig\Extension;

use OxidEsales\ExamplesModule\Greeting\Twig\Extension\ExampleExtension;
use OxidEsales\ExamplesModule\Greeting\Twig\Extension\ExampleLogicInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

final class ExampleExtensionTest extends TestCase
{
    #[Test]
    public function getFunctionsRegistersExampleFunction(): void
    {
        $logicStub = $this->createStub(ExampleLogicInterface::class);

        $container = $this->createStub(ContainerInterface::class);
        $container->method('get')
            ->willReturnMap([
                [ExampleLogicInterface::class, $logicStub],
            ]);

        $sut = $this->getSut(
            container: $container,
        );
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

    private function getSut(
        ContainerInterface $container,
    ): ExtensionInterface {
        return new ExampleExtension(
            container: $container,
        );
    }
}
