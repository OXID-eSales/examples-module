<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\ApiEntrypoint\ProductInfo\Dao;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\TableViewNameGenerator;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\ApiEntrypoint\ProductInfo\Dao\ActiveProductCountDao;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(ActiveProductCountDao::class)]
final class ActiveProductCountDaoTest extends IntegrationTestCase
{
    #[Test]
    public function countReturnsZeroWhenNoActiveProducts(): void
    {
        $this->deleteTableContent('oxarticles');

        $sut = $this->getSut();

        $this->assertSame(0, $sut->getActiveProductCount());
    }

    #[Test]
    public function countReturnsOnlyActiveParentProducts(): void
    {
        $this->deleteTableContent('oxarticles');

        $parentId = '_tart' . substr(uniqid('', true), 0, 22);
        $this->createArticle(id: $parentId, active: true);
        $this->createArticle(
            id: '_tart' . substr(uniqid('', true), 0, 22),
            active: true,
            parentId: $parentId,
        );
        $this->createArticle(
            id: '_tart' . substr(uniqid('', true), 0, 22),
            active: false,
        );

        $sut = $this->getSut();

        $this->assertSame(1, $sut->getActiveProductCount());
    }

    #[Test]
    public function countReflectsMultipleActiveProducts(): void
    {
        $this->deleteTableContent('oxarticles');

        $count = mt_rand(2, 5);
        for ($i = 0; $i < $count; $i++) {
            $this->createArticle(
                id: '_tart' . substr(uniqid('', true), 0, 22),
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
            viewNameGenerator: oxNew(TableViewNameGenerator::class),
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
            'oxtitle' => uniqid('title_', true),
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
