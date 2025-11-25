<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Tracker\Infrastructure\Repository;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\Tracker\Infrastructure\Repository\TrackerRepository;
use OxidEsales\ExamplesModule\Tracker\Infrastructure\Repository\TrackerRepositoryInterface;
use OxidEsales\ExamplesModule\Tracker\Model\TrackerModel;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TrackerRepository::class)]
final class TrackerRepositoryTest extends IntegrationTestCase
{
    private const TEST_TRACKER_ID = '_testoxid';

    private const TEST_USER_ID = '_testuser';

    private const TEST_TRACKER_COUNT = 5;

    public function setUp(): void
    {
        parent::setUp();

        $this->cleanUpTrackers();
    }

    private function cleanUpTrackers()
    {
        $queryBuilder = $this->get(QueryBuilderFactoryInterface::class)->create();
        $queryBuilder->delete('oeem_tracker');
        $queryBuilder->execute();
    }

    public function testGetExistingTrackerByUserId(): void
    {
        $this->prepareTestData();

        $sut = $this->getSut();
        $tracker = $sut->getTrackerByUserId(self::TEST_USER_ID);

        $this->assertSame(self::TEST_TRACKER_COUNT, $tracker->getCount());
    }

    public function testGetNotExistingTrackerByUserIdGivesZeroCount(): void
    {
        $sut = $this->getSut();
        $tracker = $sut->getTrackerByUserId('_notexisting');

        $this->assertSame(0, $tracker->getCount());
    }

    private function getSut(): TrackerRepositoryInterface
    {
        return $this->get(TrackerRepositoryInterface::class);
    }

    private function prepareTestData(): void
    {
        $tracker = oxNew(TrackerModel::class);
        $tracker->assign(
            [
                'oxid' => self::TEST_TRACKER_ID,
                'oxshopid' => '1',
                'oxuserid' => self::TEST_USER_ID,
                'oeemcount' => self::TEST_TRACKER_COUNT,
            ]
        );
        $tracker->save();
    }
}
