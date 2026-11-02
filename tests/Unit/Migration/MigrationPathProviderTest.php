<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Migration;

use OxidEsales\EshopCommunity\Internal\Framework\Migration\MigrationPathProviderInterface;
use OxidEsales\ExamplesModule\Migration\MigrationPathProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MigrationPathProviderTest extends TestCase
{
    #[Test]
    public function migrationConfigPathPointsToModuleMigrationsFile(): void
    {
        $moduleRootPath = dirname(__DIR__, 3);
        $expectedPath = $moduleRootPath . '/di_migrations/migrations.yaml';

        $sut = $this->getSut();

        $actualPath = $sut->getMigrationConfigPath();

        $this->assertSame($expectedPath, $actualPath);
        $this->assertFileExists($actualPath);
    }

    private function getSut(): MigrationPathProviderInterface
    {
        return new MigrationPathProvider();
    }
}
