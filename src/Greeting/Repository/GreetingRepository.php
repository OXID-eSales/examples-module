<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Repository;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\ExamplesModule\Extension\Model\User;

readonly class GreetingRepository implements GreetingRepositoryInterface
{
    public function __construct(
        private QueryBuilderFactoryInterface $queryBuilderFactory,
    ) {
    }

    public function getSavedUserGreeting(string $userId): string
    {
        $queryBuilder = $this->queryBuilderFactory->create();

        $parameters = [
            'oxuserid' => $userId,
        ];

        $result = $queryBuilder->select(User::OEEM_USER_GREETING_FIELD)
            ->from('oxuser')
            ->where('oxid = :oxuserid')
            ->setParameters($parameters)
            ->setMaxResults(1)
            ->execute();

        if (is_object($result)) {
            $value = (string)$result->fetchOne();
        }

        return $value ?? '';
    }
}
