<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\Dao;

use Doctrine\DBAL\Result;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\ExamplesModule\ProductVote\DataObject\VoteResult;
use OxidEsales\ExamplesModule\ProductVote\DataObject\VoteResultInterface;

readonly class VoteResultDao implements VoteResultDaoInterface
{
    public function __construct(
        private QueryBuilderFactoryInterface $queryBuilderFactory,
    ) {
    }

    public function getProductVoteResult(string $productId): VoteResultInterface
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->select([
                'oxartid as ProductId',
                'SUM(oxvote != 0) as VoteUp',
                'SUM(oxvote = 0) as VoteDown',
            ])
            ->from('oeem_product_vote')
            ->where('oxartid = :productId')
            ->groupBy('oxartid')
            ->setParameters([
                'productId' => $productId,
            ]);

        /** @var Result $queryResult */
        $queryResult = $queryBuilder->execute();
        $row = $queryResult->fetchAssociative();

        if (!$row) {
            return new VoteResult($productId, 0, 0);
        }
        return new VoteResult($row['ProductId'], (int)$row['VoteUp'], (int)$row['VoteDown']);
    }
}
