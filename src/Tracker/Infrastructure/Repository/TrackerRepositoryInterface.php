<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tracker\Infrastructure\Repository;

use OxidEsales\ExamplesModule\Tracker\Model\TrackerInterface;

/**
 * @extendable-class
 */
interface TrackerRepositoryInterface
{
    public function getTrackerByUserId(string $userId): TrackerInterface;
}
