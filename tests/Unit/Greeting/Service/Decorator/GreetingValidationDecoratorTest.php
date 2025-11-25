<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Greeting\Service\Decorator;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ExamplesModule\Greeting\Service\Decorator\GreetingValidationDecorator;
use OxidEsales\ExamplesModule\Greeting\Service\GreetingMessageServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(GreetingValidationDecorator::class)]
final class GreetingValidationDecoratorTest extends TestCase
{
    #[Test]
    public function getGeneralGreetingDelegatesToInnerService(): void
    {
        $expectedGreeting = uniqid('greeting');
        $innerService = $this->createMock(GreetingMessageServiceInterface::class);
        $innerService->expects($this->once())
            ->method('getGeneralGreeting')
            ->willReturn($expectedGreeting);

        $sut = $this->getSut(originalService: $innerService);

        $this->assertSame($expectedGreeting, $sut->getGeneralGreeting());
    }

    #[Test]
    public function getGreetingDelegatesToInnerService(): void
    {
        $expectedGreeting = uniqid('greeting');
        $userMock = $this->createMock(User::class);

        $innerService = $this->createMock(GreetingMessageServiceInterface::class);
        $innerService->expects($this->once())
            ->method('getGreeting')
            ->with($userMock)
            ->willReturn($expectedGreeting);

        $sut = $this->getSut(originalService: $innerService);

        $this->assertSame($expectedGreeting, $sut->getGreeting($userMock));
    }

    #[Test]
    #[DataProvider('truncationProvider')]
    public function saveGreetingForCurrentUserTruncatesLongMessages(
        string $inputMessage,
        string $expectedMessage
    ): void {
        $innerService = $this->createMock(GreetingMessageServiceInterface::class);
        $innerService->expects($this->once())
            ->method('saveGreetingForCurrentUser')
            ->with($expectedMessage);

        $sut = $this->getSut(originalService: $innerService);
        $sut->saveGreetingForCurrentUser($inputMessage);
    }

    public static function truncationProvider(): array
    {
        return [
            'exactly 50 chars' => [
                str_repeat('a', 50),
                str_repeat('a', 50),
            ],
            'exactly 51 chars - truncated to 50' => [
                str_repeat('a', 51),
                str_repeat('a', 50),
            ],
            'long message - 100 chars truncated to 50' => [
                str_repeat('a', 100),
                str_repeat('a', 50),
            ],
            'message with multibyte chars' => [
                'Это очень длинное приветствие которое должно быть обрезано',
                'Это очень длинное приветствие которое должно быть ',
            ],
            'short message passed through' => [
                'Hello',
                'Hello',
            ],
            'empty string passed through' => [
                '',
                '',
            ],
        ];
    }

    private function getSut(
        ?GreetingMessageServiceInterface $originalService = null
    ): GreetingValidationDecorator {
        return new GreetingValidationDecorator(
            originalService: $originalService ?? $this->createStub(GreetingMessageServiceInterface::class)
        );
    }
}
