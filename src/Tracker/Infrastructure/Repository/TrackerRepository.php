<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tracker\Infrastructure\Repository;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;
use OxidEsales\ExamplesModule\Tracker\Infrastructure\Factory\TrackerModelFactoryInterface;
use OxidEsales\ExamplesModule\Tracker\Model\TrackerInterface;

/**
 * @extendable-class
 */
readonly class TrackerRepository implements TrackerRepositoryInterface
{
    public function __construct(
        private QueryBuilderFactoryInterface $queryBuilderFactory,
        private ContextInterface $context,
        private TrackerModelFactoryInterface $trackerModelFactory,
    ) {
    }

    public function getTrackerByUserId(string $userId): TrackerInterface
    {
        $trackerModel = $this->trackerModelFactory->create();
        $trackerId = $this->getGreetingTrackerId($userId);

        if ($trackerId) {
            $trackerModel->load($trackerId);
        }

        //if it cannot be loaded from database, create a new object
        if (!$trackerModel->isLoaded()) {
            $trackerModel->assign([
                'oxuserid' => $userId,
                'oxshopid' => $this->context->getCurrentShopId(),
            ]);
        }

        return $trackerModel;
    }

    private function getGreetingTrackerId(string $userId): string
    {
        $queryBuilder = $this->queryBuilderFactory->create();

        $parameters = [
            'oxuserid' => $userId,
            'oxshopid' => $this->context->getCurrentShopId(),
        ];

        $result = $queryBuilder->select('oxid')
            ->from('oeem_tracker')
            ->where('oxuserid = :oxuserid')
            ->andWhere('oxshopid = :oxshopid')
            ->setParameters($parameters)
            ->setMaxResults(1)
            ->execute();

        if (is_object($result)) {
            $value = (string)$result->fetchOne();
        }

        return $value ?? '';
    }
}
