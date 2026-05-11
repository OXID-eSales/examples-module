<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Twig\Extension;

use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ExampleExtension extends AbstractExtension
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('example', [
                $this->container->get(ExampleLogicInterface::class),
                'exampleMethod'
            ])
        ];
    }
}
