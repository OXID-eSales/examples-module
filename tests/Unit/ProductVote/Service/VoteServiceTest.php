<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ProductVote\Service;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\ExamplesModule\ProductVote\Dao\ProductVoteDaoInterface;
use OxidEsales\ExamplesModule\ProductVote\Dao\VoteResultDaoInterface;
use OxidEsales\ExamplesModule\ProductVote\DataObject\ProductVote;
use OxidEsales\ExamplesModule\ProductVote\DataObject\VoteResult;
use OxidEsales\ExamplesModule\ProductVote\Service\VoteService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(VoteService::class)]
final class VoteServiceTest extends TestCase
{
    private const TEST_PRODUCT_ID = 'test_product_id';
    private const TEST_USER_ID = 'test_user_id';

    private Article $productStub;
    private User $userStub;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productStub = $this->createConfiguredStub(Article::class, ['getId' => self::TEST_PRODUCT_ID]);
        $this->userStub = $this->createConfiguredStub(User::class, ['getId' => self::TEST_USER_ID]);
    }

    #[Test]
    public function getProductVoteCallsCorrespondingDaoMethod(): void
    {
        $productVoteDaoSpy = $this->createMock(ProductVoteDaoInterface::class);
        $productVoteDaoSpy->expects($this->once())->method('getProductVote')
            ->with(self::TEST_PRODUCT_ID, self::TEST_USER_ID)->willReturn($this->getProductVoteDataType());

        $sut = $this->getSut(productVoteDao: $productVoteDaoSpy);
        $productVote = $sut->getProductVote($this->productStub, $this->userStub);

        $this->assertEquals($productVote, $this->getProductVoteDataType());
    }

    #[Test]
    public function setProductVoteCallsCorrespondingDaoMethod(): void
    {
        $productVoteDaoSpy = $this->createMock(ProductVoteDaoInterface::class);
        $productVoteDaoSpy->expects($this->once())->method('setProductVote')->with($this->getProductVoteDataType());

        $sut = $this->getSut(productVoteDao: $productVoteDaoSpy);
        $sut->setProductVote($this->productStub, $this->userStub, true);
    }

    #[Test]
    public function resetProductVoteCallsCorrespondingDaoMethod(): void
    {
        $productVoteDaoSpy = $this->createMock(ProductVoteDaoInterface::class);
        $productVoteDaoSpy->expects($this->once())
            ->method('resetProductVote')->with(self::TEST_PRODUCT_ID, self::TEST_USER_ID);

        $sut = $this->getSut(productVoteDao: $productVoteDaoSpy);
        $sut->resetProductVote($this->productStub, $this->userStub);
    }

    #[Test]
    public function getProductVoteResultCallsCorrespondingDaoMethod(): void
    {
        $voteResultDaoSpy = $this->createMock(VoteResultDaoInterface::class);
        $voteResultDaoSpy->expects($this->once())
            ->method('getProductVoteResult')->with(self::TEST_PRODUCT_ID)->willReturn($this->getVoteResultDataType());

        $sut = $this->getSut(voteResultDao: $voteResultDaoSpy);
        $voteResult = $sut->getProductVoteResult($this->productStub);

        $this->assertEquals($voteResult, $this->getVoteResultDataType());
    }

    private function getSut(
        ?ProductVoteDaoInterface $productVoteDao = null,
        ?VoteResultDaoInterface $voteResultDao = null,
    ): VoteService {
        return new VoteService(
            productVoteDao: $productVoteDao ?? $this->createStub(ProductVoteDaoInterface::class),
            voteResultDao: $voteResultDao ?? $this->createStub(VoteResultDaoInterface::class),
        );
    }

    private function getProductVoteDataType(): ProductVote
    {
        return new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, true);
    }

    private function getVoteResultDataType(): VoteResult
    {
        return new VoteResult(self::TEST_PRODUCT_ID, 3, 2);
    }
}
