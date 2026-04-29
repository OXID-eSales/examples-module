<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Infrastructure;

use Doctrine\DBAL\Result;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\DTO\CustomerGroupCount;

readonly class CustomerGroupRepository implements CustomerGroupRepositoryInterface
{
    public function __construct(
        private QueryBuilderFactoryInterface $queryBuilderFactory,
    ) {
    }

    /** @return list<CustomerGroupCount> */
    public function getCustomerGroupCounts(): array
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->select([
                'g.oxid AS groupId',
                'g.oxtitle AS title',
                'COUNT(u2g.oxid) AS customerCount',
            ])
            ->from('oxgroups', 'g')
            ->leftJoin(
                'g',
                'oxobject2group',
                'u2g',
                'g.oxid = u2g.oxgroupsid'
            )
            ->where('g.oxactive = 1')
            ->groupBy('g.oxid, g.oxtitle')
            ->orderBy('g.oxtitle', 'ASC');

        /** @var Result $result */
        $result = $queryBuilder->execute();
        $rows = $result->fetchAllAssociative();

        $counts = [];
        foreach ($rows as $row) {
            $counts[] = new CustomerGroupCount(
                groupId: $row['groupId'],
                title: $row['title'],
                count: (int) $row['customerCount'],
            );
        }

        return $counts;
    }
}
