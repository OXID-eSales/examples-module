<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\ProductVote\Widget;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ExamplesModule\ProductVote\DataObject\ProductVoteInterface;
use OxidEsales\ExamplesModule\ProductVote\DataObject\VoteResultInterface;
use OxidEsales\ExamplesModule\ProductVote\Service\VoteServiceInterface;
use OxidEsales\ExamplesModule\ProductVote\Widget\ArticleDetails;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ArticleDetails::class)]
final class ArticleDetailsTest extends TestCase
{
    #[Test]
    public function prepareDataForNotLoggedInUserGivesPartialData(): void
    {
        $voteResultStub = $this->createStub(VoteResultInterface::class);

        $sut = $this->getSutMock(voteResult: $voteResultStub);
        $sut->oeemPrepareVoteData();

        $viewData = $sut->getViewData();
        $this->assertEquals($voteResultStub, $viewData['productVoteResult']);
        $this->assertArrayNotHasKey('productVote', $viewData);
    }

    #[Test]
    public function prepareDataForLoggedInUserGivesFullData(): void
    {
        $voteResultStub = $this->createStub(VoteResultInterface::class);
        $productVoteStub = $this->createStub(ProductVoteInterface::class);

        $sut = $this->getSutMock(
            voteResult: $voteResultStub,
            productVote: $productVoteStub,
            user: $this->createStub(user::class)
        );
        $sut->oeemPrepareVoteData();

        $viewData = $sut->getViewData();
        $this->assertEquals($voteResultStub, $viewData['productVoteResult']);
        $this->assertEquals($productVoteStub, $viewData['productVote']);
    }

    private function getSutMock(
        VoteResultInterface $voteResult,
        ?ProductVoteInterface $productVote = null,
        ?User $user = null,
    ): ArticleDetails {
        $productStub = $this->createStub(Article::class);
        $voteServiceStub = $this->createConfiguredStub(VoteServiceInterface::class, [
            'getProductVote' => $productVote,
            'getProductVoteResult' => $voteResult,
        ]);

        $sut = $this->getMockBuilder(ArticleDetails::class)
            ->onlyMethods(['getProduct', 'getUser', 'getService'])->getMock();
        $sut->method('getProduct')->willReturn($productStub);
        $sut->method('getUser')->willReturn($user);
        $sut->method('getService')->with(VoteServiceInterface::class)->willReturn($voteServiceStub);

        return $sut;
    }
}
