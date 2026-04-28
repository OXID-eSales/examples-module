<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Dao;

use Doctrine\DBAL\Result;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Adapter\ShopAdapterInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;

readonly class ActiveProductCountDao implements ActiveProductCountDaoInterface
{
    public function __construct(
        private QueryBuilderFactoryInterface $queryBuilderFactory,
        private ShopAdapterInterface $shopAdapter,
        private ContextInterface $context,
    ) {
    }

    public function getActiveProductCount(): int
    {
        $tableName = $this->shopAdapter->generateDatabaseViewName(
            'oxarticles',
            0,
            $this->context->getCurrentShopId()
        );

        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select('COUNT(*)')
            ->from($tableName)
            ->where('oxactive = 1')
            ->andWhere('oxparentid = :parentId')
            ->setParameter('parentId', '');

        /** @var Result $result */
        $result = $queryBuilder->execute();

        return (int)$result->fetchOne();
    }
}
