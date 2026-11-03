<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Tracker\Subscriber;

use OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\BeforeModelUpdateEvent;
use OxidEsales\ExamplesModule\Extension\Model\User;
use OxidEsales\ExamplesModule\Tracker\Model\TrackerModel;
use OxidEsales\ExamplesModule\Tracker\Service\TrackerServiceInterface;
use OxidEsales\ExamplesModule\Tracker\Subscriber\BeforeModelUpdate;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(BeforeModelUpdate::class)]
final class BeforeModelUpdateTest extends TestCase
{
    #[Test]
    public function handleEventWithNotMatchingPayload(): void
    {
        $event = $this->createConfiguredStub(BeforeModelUpdateEvent::class, [
            'getModel' => $this->createStub(TrackerModel::class)
        ]);

        $trackerSpy = $this->createMock(TrackerServiceInterface::class);
        $trackerSpy->expects($this->never())->method('updateTracker');

        $sut = $this->getSut(
            trackerService: $trackerSpy,
        );

        $sut->handle($event);
    }

    #[Test]
    public function handleEventWithMatchingPayload(): void
    {
        $event = $this->createConfiguredStub(BeforeModelUpdateEvent::class, [
            'getModel' => $this->createStub(User::class)
        ]);

        $trackerSpy = $this->createMock(TrackerServiceInterface::class);
        $trackerSpy->expects($this->once())->method('updateTracker');

        $sut = $this->getSut(
            trackerService: $trackerSpy,
        );

        $sut->handle($event);
    }

    #[Test]
    public function subscribedEvents(): void
    {
        $handlers = BeforeModelUpdate::getSubscribedEvents();
        $this->assertArrayHasKey(BeforeModelUpdateEvent::class, $handlers);
    }

    private function getSut(
        TrackerServiceInterface $trackerService,
    ): BeforeModelUpdate {
        return new BeforeModelUpdate(
            trackerService: $trackerService,
        );
    }
}
