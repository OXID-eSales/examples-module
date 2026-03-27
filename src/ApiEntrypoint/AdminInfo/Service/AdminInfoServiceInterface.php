<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\Service;

use OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\DataObject\AdminInfo;

interface AdminInfoServiceInterface
{
    public function getAdminInfo(string $username): AdminInfo;
}
