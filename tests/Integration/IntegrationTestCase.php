<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class IntegrationTestCase extends \OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase
{
    protected function cleanUpUsers()
    {
        $queryBuilder = $this->get(QueryBuilderFactoryInterface::class)->create();
        $queryBuilder->delete('oxuser');
        $queryBuilder->execute();
    }

    protected function cleanUpTrackers()
    {
        $queryBuilder = $this->get(QueryBuilderFactoryInterface::class)->create();
        $queryBuilder->delete('oeem_tracker');
        $queryBuilder->execute();
    }

    protected function get(string $serviceId)
    {
        return ContainerFactory::getInstance()->getContainer()->get($serviceId);
    }
}
