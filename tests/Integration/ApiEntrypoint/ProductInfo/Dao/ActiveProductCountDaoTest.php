<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\ApiEntrypoint\ProductInfo\Dao;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Adapter\ShopAdapterInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Dao\ActiveProductCountDao;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(ActiveProductCountDao::class)]
final class ActiveProductCountDaoTest extends IntegrationTestCase
{
    #[Before]
    public function cleanTables(): void
    {
        $this->deleteTableContent('oxarticles');
    }

    #[Test]
    public function countReturnsZeroWhenNoActiveProducts(): void
    {
        $sut = $this->getSut();

        $this->assertSame(0, $sut->getActiveProductCount());
    }

    #[Test]
    public function countReturnsOnlyActiveParentProducts(): void
    {
        $parentId = '_tart' . substr(uniqid(''), 0, 22);
        $this->createArticle(id: $parentId, active: true);
        $this->createArticle(
            id: '_tart' . substr(uniqid(''), 0, 22),
            active: true,
            parentId: $parentId,
        );
        $this->createArticle(
            id: '_tart' . substr(uniqid(''), 0, 22),
            active: false,
        );

        $sut = $this->getSut();

        $this->assertSame(1, $sut->getActiveProductCount());
    }

    #[Test]
    public function countReflectsMultipleActiveProducts(): void
    {
        $count = mt_rand(2, 5);
        for ($i = 0; $i < $count; $i++) {
            $this->createArticle(
                id: '_tart' . substr(uniqid(''), 0, 22),
                active: true,
            );
        }

        $sut = $this->getSut();

        $this->assertSame($count, $sut->getActiveProductCount());
    }

    private function getSut(): ActiveProductCountDao
    {
        return new ActiveProductCountDao(
            queryBuilderFactory: $this->get(QueryBuilderFactoryInterface::class),
            shopAdapter: $this->get(ShopAdapterInterface::class),
            context: $this->get(ContextInterface::class),
        );
    }

    private function createArticle(
        string $id,
        bool $active,
        string $parentId = '',
    ): void {
        $article = oxNew(Article::class);
        $article->setId($id);
        $article->assign([
            'oxactive' => (int) $active,
            'oxtitle' => uniqid('title_'),
            'oxparentid' => $parentId,
            'oxartnum' => 'TEST-' . substr($id, -8),
            'oxshopid' => 1,
            'oxprice' => 10.00,
            'oxstock' => 100,
            'oxstockflag' => 1,
            'oxvarstock' => 0,
            'oxvarcount' => 0,
        ]);
        $article->save();
    }

    private function deleteTableContent(string $table): void
    {
        $this->get(QueryBuilderFactoryInterface::class)
            ->create()
            ->delete($table)
            ->execute();
    }
}
