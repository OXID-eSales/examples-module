<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\ExamplesModule\Tracker\Model;

interface TrackerInterface
{
    public function countUp(): void;

    public function getCount(): int;
}
