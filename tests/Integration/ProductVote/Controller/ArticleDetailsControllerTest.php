<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\ProductVote\Controller;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ExamplesModule\ProductVote\Controller\ArticleDetailsController;
use OxidEsales\ExamplesModule\ProductVote\Service\VoteServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ArticleDetailsController::class)]
final class ArticleDetailsControllerTest extends TestCase
{
    #[Test]
    public function ensureNotLoggedInDoesNotCallServiceMethods(): void
    {
        $voteServiceSpy = $this->createMock(VoteServiceInterface::class);
        $voteServiceSpy->expects($this->never())->method('setProductVote');
        $voteServiceSpy->expects($this->never())->method('resetProductVote');

        $productStub = $this->createStub(Article::class);

        $sut = $this->getSutMock(
            voteServiceSpy: $voteServiceSpy,
            product: $productStub
        );

        $sut->voteUp();
        $sut->voteDown();
        $sut->resetVote();
    }

    #[Test]
    public function voteUpForLoggedInUserCallsServiceMethod(): void
    {
        $productStub = $this->createStub(Article::class);
        $userStub = $this->createStub(User::class);

        $voteServiceSpy = $this->createMock(VoteServiceInterface::class);
        $voteServiceSpy->expects($this->once())->method('setProductVote')->with($productStub, $userStub, true);

        $sut = $this->getSutMock(
            voteServiceSpy: $voteServiceSpy,
            user: $userStub,
            product: $productStub,
        );

        $sut->voteUp();
    }

    #[Test]
    public function voteDownForLoggedInUserCallsServiceMethod(): void
    {
        $productStub = $this->createStub(Article::class);
        $userStub = $this->createStub(User::class);

        $voteServiceSpy = $this->createMock(VoteServiceInterface::class);
        $voteServiceSpy->expects($this->once())->method('setProductVote')->with($productStub, $userStub, false);

        $sut = $this->getSutMock(
            voteServiceSpy: $voteServiceSpy,
            user: $userStub,
            product: $productStub,
        );

        $sut->voteDown();
    }

    #[Test]
    public function resetVoteTriggersServiceWithExpectedProductAndUser(): void
    {
        $productStub = $this->createStub(Article::class);
        $userStub = $this->createStub(User::class);

        $voteServiceSpy = $this->createMock(VoteServiceInterface::class);
        $voteServiceSpy->expects($this->once())->method('resetProductVote')->with($productStub, $userStub);

        $sut = $this->getSutMock(
            voteServiceSpy: $voteServiceSpy,
            user: $userStub,
            product: $productStub
        );

        $sut->resetVote();
    }

    private function getSutMock(
        VoteServiceInterface $voteServiceSpy,
        User $user = null,
        Article $product = null,
    ): ArticleDetailsController {
        $sut = $this->getMockBuilder(ArticleDetailsController::class)
            ->onlyMethods(['getService', 'getProduct', 'getUser'])->getMock();

        $sut->method('getService')->with(VoteServiceInterface::class)->willReturn($voteServiceSpy);
        $sut->method('getUser')->willReturn($user);
        $sut->method('getProduct')->willReturn($product);

        return $sut;
    }
}
