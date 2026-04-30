<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Infrastructure;

use Doctrine\DBAL\Result;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private QueryBuilderFactoryInterface $queryBuilderFactory,
    ) {
    }

    public function getFirstNameByUsername(string $username): ?string
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->select('oxfname')
            ->from('oxuser')
            ->where('oxusername = :username')
            ->setParameter('username', $username);

        /** @var Result $dbResult */
        $dbResult = $queryBuilder->execute();
        $value = $dbResult->fetchOne();

        return $value !== false ? (string) $value : null;
    }
}
