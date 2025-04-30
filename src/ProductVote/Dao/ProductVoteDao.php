<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ProductVote\Dao;

use Doctrine\DBAL\Result;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\ExamplesModule\ProductVote\DataObject\ProductVote;
use OxidEsales\ExamplesModule\ProductVote\DataObject\ProductVoteInterface;

readonly class ProductVoteDao implements ProductVoteDaoInterface
{
    public function __construct(
        private QueryBuilderFactoryInterface $queryBuilderFactory,
    ) {
    }

    public function getProductVote(string $productId, string $userId): ?ProductVoteInterface
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->select([
                'oxartid as ProductId',
                'oxuserid as UserId',
                'oxvote as Vote',
            ])
            ->from('oeem_product_vote')
            ->where('oxartid = :productId')
            ->andWhere('oxuserid = :userId')
            ->setParameters([
                'productId' => $productId,
                'userId' => $userId,
            ]);

        /** @var Result $result */
        $result = $queryBuilder->execute();
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new ProductVote($row['ProductId'], $row['UserId'], (bool)$row['Vote']);
    }

    public function setProductVote(ProductVoteInterface $vote): void
    {
        $this->resetProductVote($vote->getProductId(), $vote->getUserId());

        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->insert('oeem_product_vote')
            ->values([
                'oxid' => ':oxid',
                'oxartid' => ':productId',
                'oxuserid' => ':userId',
                'oxvote' => ':vote',
            ])
            ->setParameters([
                'oxid' => uniqid(),
                'productId' => $vote->getProductId(),
                'userId' => $vote->getUserId(),
                'vote' => (int)$vote->isVoteUp(),
            ])
            ->execute();
    }

    public function resetProductVote(string $productId, string $userId): void
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->delete('oeem_product_vote')
            ->where('oxartid = :productId')
            ->andWhere('oxuserid = :userId')
            ->setParameters([
                'productId' => $productId,
                'userId'    => $userId,
            ])
            ->execute();
    }
}
