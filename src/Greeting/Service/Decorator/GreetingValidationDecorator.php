<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Service\Decorator;

use OxidEsales\Eshop\Application\Model\User as EshopModelUser;
use OxidEsales\ExamplesModule\Greeting\Service\GreetingMessageServiceInterface;

/**
 * Decorator that adds validation to greeting messages.
 *
 * This is an example of the Decorator pattern in Symfony DI.
 * Decorators allow you to add cross-cutting concerns (like validation,
 * logging, caching) without modifying the original service.
 *
 * Benefits:
 * - Open/Closed Principle: extend functionality without modifying existing code
 * - Single Responsibility: validation logic separate from business logic
 * - Easy to enable/disable: just register/unregister the decorator
 * - Testable: can test validation independently
 */
readonly class GreetingValidationDecorator implements GreetingMessageServiceInterface
{
    private const MAX_GREETING_LENGTH = 50;

    public function __construct(
        private GreetingMessageServiceInterface $originalService
    ) {
    }

    public function getGeneralGreeting(): string
    {
        return $this->originalService->getGeneralGreeting();
    }

    public function getGreeting(?EshopModelUser $user = null): string
    {
        return $this->originalService->getGreeting($user);
    }

    /**
     * Truncates greeting message if it exceeds maximum length before saving.
     */
    public function saveGreetingForCurrentUser(string $message): void
    {
        if (mb_strlen($message) > self::MAX_GREETING_LENGTH) {
            $message = mb_substr($message, 0, self::MAX_GREETING_LENGTH);
        }

        $this->originalService->saveGreetingForCurrentUser($message);
    }
}
