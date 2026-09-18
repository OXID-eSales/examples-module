<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Migration;

use OxidEsales\EshopCommunity\Internal\Framework\Migration\MigrationPathProviderInterface;

final class MigrationPathProvider implements MigrationPathProviderInterface
{
    public function getMigrationConfigPath(): string
    {
        return dirname(__DIR__, 2) . '/di_migrations/migrations.yaml';
    }
}
