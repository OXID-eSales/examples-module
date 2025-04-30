<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\ProductVote\Dao;

use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\ProductVote\Dao\ProductVoteDao;
use OxidEsales\ExamplesModule\ProductVote\Dao\ProductVoteDaoInterface;
use OxidEsales\ExamplesModule\ProductVote\DataObject\ProductVote;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(ProductVoteDao::class)]
final class ProductVoteDaoTest extends IntegrationTestCase
{
    private const TEST_USER_ID = '_testuser';
    private const TEST_PRODUCT_ID = '_testproduct';

    #[Test]
    public function noVoteForNoRecord(): void
    {
        /** @var ProductVoteDaoInterface $sut */
        $sut = $this->get(ProductVoteDaoInterface::class);

        $productVote = $sut->getProductVote('productId', 'userId');
        $this->assertNull($productVote);
    }

    #[Test]
    #[DataProvider('isVoteUpProvider')]
    public function setAndGetVoteGivesTheSameVote(bool $isVoteUp): void
    {
        /** @var ProductVoteDaoInterface $sut */
        $sut = $this->get(ProductVoteDaoInterface::class);

        $vote = new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, $isVoteUp);
        $sut->setProductVote($vote);

        $daoVote = $sut->getProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID);
        $this->assertNotSame($vote, $daoVote);

        $this->assertEquals(self::TEST_PRODUCT_ID, $daoVote->getProductId());
        $this->assertEquals(self::TEST_USER_ID, $daoVote->getUserId());
        $this->assertEquals($isVoteUp, $daoVote->isVoteUp());
    }

    public static function isVoteUpProvider(): array
    {
        return [
            ['isVoteUp' => true],
            ['isVoteUp' => false],
        ];
    }

    #[Test]
    public function setVoteForTheSameUserAndProductReplacesVote(): void
    {
        $upVote = new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, true);
        $downVote = new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, false);

        /** @var ProductVoteDaoInterface $sut */
        $sut = $this->get(ProductVoteDaoInterface::class);
        $sut->setProductVote($upVote);
        $sut->setProductVote($downVote);

        $vote = $sut->getProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID);
        $this->assertEquals($downVote, $vote);
    }

    #[Test]
    public function resetNonExistingVoteDoesNothing(): void
    {
        /** @var ProductVoteDaoInterface $sut */
        $sut = $this->get(ProductVoteDaoInterface::class);
        $sut->resetProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID);

        $vote = $sut->getProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID);
        $this->assertNull($vote);
    }

    #[Test]
    public function resetVoteRemovesVote(): void
    {
        /** @var ProductVoteDaoInterface $sut */
        $sut = $this->get(ProductVoteDaoInterface::class);
        $sut->setProductVote(new ProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID, true));

        $sut->resetProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID);

        $vote = $sut->getProductVote(self::TEST_PRODUCT_ID, self::TEST_USER_ID);
        $this->assertNull($vote);
    }
}
