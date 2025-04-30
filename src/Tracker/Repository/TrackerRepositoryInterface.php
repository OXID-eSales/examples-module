<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tracker\Repository;

use OxidEsales\ExamplesModule\Tracker\Model\TrackerModel;

/**
 * @extendable-class
 */
interface TrackerRepositoryInterface
{
    public function getTrackerByUserId(string $userId): TrackerModel;
}
