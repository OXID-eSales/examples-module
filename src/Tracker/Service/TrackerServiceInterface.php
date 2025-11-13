<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tracker\Service;

use OxidEsales\ExamplesModule\Extension\Model\UserInterface;

interface TrackerServiceInterface
{
    public function updateTracker(UserInterface $user): void;
}
