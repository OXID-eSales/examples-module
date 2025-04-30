<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tracker\Service;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ExamplesModule\Greeting\Model\PersonalGreetingUserInterface;
use OxidEsales\ExamplesModule\Greeting\Repository\GreetingRepositoryInterface;
use OxidEsales\ExamplesModule\Tracker\Repository\TrackerRepositoryInterface;

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

    public function updateTracker(User&PersonalGreetingUserInterface $user): void
    {
        $savedGreeting = $this->greetingRepository->getSavedUserGreeting($user->getId());

        if ($savedGreeting !== $user->getPersonalGreeting()) {
            $tracker = $this->trackerRepository->getTrackerByUserId($user->getId());
            $tracker->countUp();
        }
    }
}
