<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Tracker\Repository;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\Tracker\Model\TrackerModel;
use OxidEsales\ExamplesModule\Tracker\Repository\TrackerRepository;
use OxidEsales\ExamplesModule\Tracker\Repository\TrackerRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TrackerRepository::class)]
final class TrackerRepositoryTest extends IntegrationTestCase
{
    public const TEST_TRACKER_ID = '_testoxid';

    public const TEST_USER_ID = '_testuser';

    public const TEST_GREETING = 'Hi there';

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

        $sut = $this->get(TrackerRepositoryInterface::class);
        $tracker = $sut->getTrackerByUserId(self::TEST_USER_ID);

        $this->assertSame(self::TEST_TRACKER_ID, $tracker->getId());
    }

    public function testGetNotExistingTrackerByUserId(): void
    {
        $sut = $this->get(TrackerRepositoryInterface::class);
        $tracker = $sut->getTrackerByUserId('_notexisting');

        $this->assertEmpty($tracker->getId());
        $this->assertSame('_notexisting', $tracker->getFieldData('oxuserid'));
    }

    private function prepareTestData(): void
    {
        $tracker = oxNew(TrackerModel::class);
        $tracker->assign(
            [
                'oxid' => self::TEST_TRACKER_ID,
                'oxshopid' => '1',
                'oxuserid' => self::TEST_USER_ID,
                'oeemcount' => 5,
            ]
        );
        $tracker->save();
    }
}
