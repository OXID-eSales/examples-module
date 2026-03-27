<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\Service;

use OxidEsales\EshopCommunity\Internal\Transition\Adapter\ShopAdapterInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\DataObject\AdminInfo;
use OxidEsales\ExamplesModule\Core\Module as ModuleCore;

readonly class AdminInfoService implements AdminInfoServiceInterface
{
    public function __construct(
        private ShopAdapterInterface $shopAdapter,
    ) {
    }

    public function getAdminInfo(string $username): AdminInfo
    {
        $greetingPattern = $this->shopAdapter->translateString(
            ModuleCore::ADMIN_HELLO_LANGUAGE_CONST
        );

        return new AdminInfo(
            email: $username,
            greeting: sprintf($greetingPattern, $username),
        );
    }
}
