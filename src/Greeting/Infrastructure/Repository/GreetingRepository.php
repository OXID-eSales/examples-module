<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

readonly class GreetingRepository implements GreetingRepositoryInterface
{
    public const OEEM_USER_GREETING_FIELD = 'oeemgreeting';

    public function __construct(
        private QueryBuilderFactoryInterface $queryBuilderFactory,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function getSavedUserGreeting(string $userId): string
    {
        $queryBuilder = $this->queryBuilderFactory->create();

        $parameters = [
            'oxuserid' => $userId,
        ];

        $result = $queryBuilder->select(self::OEEM_USER_GREETING_FIELD)
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

    public function saveGreetingForActiveUser(string $greeting): void
    {
        $user = $this->userRepository->getActiveUser();
        $user->assign([
            self::OEEM_USER_GREETING_FIELD => $greeting,
        ]);

        //todo: save via user repository?
        $user->save();
    }
}
