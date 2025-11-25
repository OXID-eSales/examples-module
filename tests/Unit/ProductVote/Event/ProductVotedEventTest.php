<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ProductVote\Event;

use OxidEsales\ExamplesModule\ProductVote\DataObject\ProductVoteInterface;
use OxidEsales\ExamplesModule\ProductVote\Event\ProductVotedEvent;
use OxidEsales\ExamplesModule\ProductVote\Event\ProductVotedEventInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\EventDispatcher\Event;

#[CoversClass(ProductVotedEvent::class)]
final class ProductVotedEventTest extends TestCase
{
    #[Test]
    public function implementsProductVotedEventInterface(): void
    {
        $productVote = $this->createStub(ProductVoteInterface::class);
        $sut = new ProductVotedEvent($productVote);

        $this->assertInstanceOf(ProductVotedEventInterface::class, $sut);
    }

    #[Test]
    public function extendsSymfonyEvent(): void
    {
        $productVote = $this->createStub(ProductVoteInterface::class);
        $sut = new ProductVotedEvent($productVote);

        $this->assertInstanceOf(Event::class, $sut);
    }

    #[Test]
    public function getProductVoteReturnsConstructorArgument(): void
    {
        $productVote = $this->createStub(ProductVoteInterface::class);
        $sut = new ProductVotedEvent($productVote);

        $this->assertSame($productVote, $sut->getProductVote());
    }
}
