<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Tracker\Infrastructure\Factory;

use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\Tracker\Infrastructure\Factory\TrackerModelFactory;
use OxidEsales\ExamplesModule\Tracker\Model\TrackerModel;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TrackerModelFactory::class)]
final class TrackerModelFactoryTest extends IntegrationTestCase
{
    public function testCreateProducesNewModelObjectEveryCall(): void
    {
        $sut = new TrackerModelFactory();

        $firstModel = $sut->create();
        $secondModel = $sut->create();

        $this->assertInstanceOf(TrackerModel::class, $firstModel);
        $this->assertInstanceOf(TrackerModel::class, $secondModel);
        $this->assertNotSame($firstModel, $secondModel);
    }
}
