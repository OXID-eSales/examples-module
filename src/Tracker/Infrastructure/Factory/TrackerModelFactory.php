<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tracker\Infrastructure\Factory;

use OxidEsales\ExamplesModule\Tracker\Model\TrackerModel;

class TrackerModelFactory implements TrackerModelFactoryInterface
{
    public function create(): TrackerModel
    {
        return oxNew(TrackerModel::class);
    }
}
