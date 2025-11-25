<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tracker\Service;

use OxidEsales\ExamplesModule\Extension\Model\UserInterface;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\GreetingRepositoryInterface;
use OxidEsales\ExamplesModule\Tracker\Infrastructure\Repository\TrackerRepositoryInterface;

/**
 * Example which we can decorate
 */
readonly class TrackerService implements TrackerServiceInterface
{
    public function __construct(
        private TrackerRepositoryInterface $trackerRepository,
        private GreetingRepositoryInterface $greetingRepository,
    ) {
    }

    public function updateTracker(UserInterface $user): void
    {
        if (empty($user->getId())) {
            return;
        }

        $savedGreeting = $this->greetingRepository->getSavedUserGreeting($user->getId());

        if ($savedGreeting !== $user->getPersonalGreeting()) {
            $tracker = $this->trackerRepository->getTrackerByUserId($user->getId());
            $tracker->countUp();
        }
    }
}
