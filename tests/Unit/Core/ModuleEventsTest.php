<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Core;

use OxidEsales\ExamplesModule\Core\ModuleEvents;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ModuleEvents::class)]
class ModuleEventsTest extends TestCase
{
    public function testEventsExecutable(): void
    {
        ModuleEvents::onActivate();
        ModuleEvents::onDeactivate();

        $this->addToAssertionCount(2);
    }
}
