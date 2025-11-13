<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Tracker\Service;

use OxidEsales\ExamplesModule\Extension\Model\UserInterface;
use OxidEsales\ExamplesModule\Greeting\Repository\GreetingRepositoryInterface;
use OxidEsales\ExamplesModule\Tracker\Infrastructure\Repository\TrackerRepositoryInterface;
use OxidEsales\ExamplesModule\Tracker\Model\TrackerModel;
use OxidEsales\ExamplesModule\Tracker\Service\TrackerService;
use OxidEsales\ExamplesModule\Tracker\Service\TrackerServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TrackerService::class)]
final class TrackerServiceTest extends TestCase
{
    public const TEST_TRACKER_ID = '_testoxid';

    public const TEST_USER_ID = '_testuser';

    public const TEST_GREETING = 'Hi there';

    public function testUpdateTrackerNoGreetingChangeIfItsTheSame(): void
    {
        $greetingRepository = $this->createConfiguredStub(GreetingRepositoryInterface::class, [
            'getSavedUserGreeting' => self::TEST_GREETING
        ]);

        $trackerRepository = $this->createPartialMock(TrackerRepositoryInterface::class, ['getTrackerByUserId']);
        $trackerRepository->expects($this->never())->method('getTrackerByUserId');

        $sut = $this->getSut(
            trackerRepository: $trackerRepository,
            greetingRepository: $greetingRepository
        );

        $user = $this->createConfiguredStub(UserInterface::class, [
            'getId' => self::TEST_USER_ID,
            'getPersonalGreeting' => self::TEST_GREETING,
        ]);

        $sut->updateTracker($user);
    }

    public function testUpdateTrackerGreetingChangeIfItDiffers(): void
    {
        $greetingRepository = $this->createConfiguredStub(GreetingRepositoryInterface::class, [
            'getSavedUserGreeting' => self::TEST_GREETING . ' with a change'
        ]);

        $trackerRepository = $this->createConfiguredStub(TrackerRepositoryInterface::class, [
            'getTrackerByUserId' => $trackerSpy = $this->createMock(TrackerModel::class)
        ]);
        $trackerSpy->expects($this->once())->method('countUp');

        $sut = $this->getSut(
            trackerRepository: $trackerRepository,
            greetingRepository: $greetingRepository
        );

        $user = $this->createConfiguredStub(UserInterface::class, [
            'getId' => self::TEST_USER_ID,
            'getPersonalGreeting' => self::TEST_GREETING,
        ]);

        $sut->updateTracker($user);
    }

    public function testUpdateTrackerWithUserWithoutIdIsNotDoingAnything(): void
    {
        $greetingRepository = $this->createMock(GreetingRepositoryInterface::class);
        $greetingRepository->expects($this->never())->method('getSavedUserGreeting');

        $trackerRepository = $this->createMock(TrackerRepositoryInterface::class);
        $trackerRepository->expects($this->never())->method('getTrackerByUserId');

        $sut = $this->getSut(
            trackerRepository: $trackerRepository,
            greetingRepository: $greetingRepository
        );

        $user = $this->createConfiguredStub(UserInterface::class, [
            'getId' => '',
            'getPersonalGreeting' => self::TEST_GREETING,
        ]);

        $sut->updateTracker($user);
    }

    private function getSut(
        ?TrackerRepositoryInterface $trackerRepository = null,
        ?GreetingRepositoryInterface $greetingRepository = null,
    ): TrackerServiceInterface {
        $trackerRepository ??= $this->createStub(TrackerRepositoryInterface::class);
        $greetingRepository ??= $this->createStub(GreetingRepositoryInterface::class);

        return new TrackerService(
            $trackerRepository,
            $greetingRepository
        );
    }
}
