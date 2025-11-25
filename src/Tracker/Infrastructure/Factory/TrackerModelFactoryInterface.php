<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\ExamplesModule\Tracker\Infrastructure\Factory;

use OxidEsales\ExamplesModule\Tracker\Model\TrackerModel;

interface TrackerModelFactoryInterface
{
    public function create(): TrackerModel;
}
