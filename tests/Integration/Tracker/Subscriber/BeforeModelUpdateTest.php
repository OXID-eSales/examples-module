<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Tracker\Subscriber;

use OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\BeforeModelUpdateEvent;
use OxidEsales\ExamplesModule\Extension\Model\User;
use OxidEsales\ExamplesModule\Tracker\Model\TrackerModel;
use OxidEsales\ExamplesModule\Tracker\Service\TrackerServiceInterface;
use OxidEsales\ExamplesModule\Tracker\Subscriber\BeforeModelUpdate;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BeforeModelUpdate::class)]
final class BeforeModelUpdateTest extends TestCase
{
    public function testHandleEventWithNotMatchingPayload(): void
    {
        $event = $this->createConfiguredStub(BeforeModelUpdateEvent::class, [
            'getModel' => $this->createStub(TrackerModel::class)
        ]);

        $sut = new BeforeModelUpdate(
            trackerService: $trackerSpy = $this->createMock(TrackerServiceInterface::class)
        );
        $trackerSpy->expects($this->never())->method('updateTracker');

        $sut->handle($event);
    }

    public function testHandleEventWithMatchingPayload(): void
    {
        $event = $this->createConfiguredStub(BeforeModelUpdateEvent::class, [
            'getModel' => $this->createStub(User::class)
        ]);

        $sut = new BeforeModelUpdate(
            trackerService: $trackerSpy = $this->createMock(TrackerServiceInterface::class)
        );
        $trackerSpy->expects($this->once())->method('updateTracker');

        $sut->handle($event);
    }

    public function testSubscribedEvents(): void
    {
        $handlers = BeforeModelUpdate::getSubscribedEvents();
        $this->assertArrayHasKey(BeforeModelUpdateEvent::class, $handlers);
    }
}
