<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\ApiEntrypoint\CustomerGroup\Dao;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Dao\CustomerGroupCountDao;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerGroup\Dao\CustomerGroupCountDaoInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(CustomerGroupCountDao::class)]
final class CustomerGroupCountDaoTest extends IntegrationTestCase
{
    #[Test]
    public function returnsEmptyArrayWhenNoActiveGroups(): void
    {
        $this->deleteTableContent('oxobject2group');
        $this->deleteTableContent('oxgroups');

        $sut = $this->get(CustomerGroupCountDaoInterface::class);

        $this->assertSame([], $sut->getCustomerGroupCounts());
    }

    #[Test]
    public function returnsGroupWithZeroCountWhenNoUsersAssigned(): void
    {
        $this->deleteTableContent('oxobject2group');
        $this->deleteTableContent('oxgroups');

        $groupId = '_tgrp' . substr(uniqid('', true), 0, 22);
        $groupTitle = uniqid('group_', true);
        $this->createGroup($groupId, $groupTitle);

        $sut = $this->get(CustomerGroupCountDaoInterface::class);

        $result = $sut->getCustomerGroupCounts();
        $this->assertCount(1, $result);
        $this->assertSame($groupId, $result[0]->getGroupId());
        $this->assertSame($groupTitle, $result[0]->getTitle());
        $this->assertSame(0, $result[0]->getCount());
    }

    #[Test]
    public function returnsCorrectCountPerGroup(): void
    {
        $this->deleteTableContent('oxobject2group');
        $this->deleteTableContent('oxgroups');

        $groupId = '_tgrp' . substr(uniqid('', true), 0, 22);
        $this->createGroup($groupId, uniqid());

        $userCount = mt_rand(2, 5);
        for ($i = 0; $i < $userCount; $i++) {
            $this->assignUserToGroup(
                '_tusr' . substr(uniqid('', true), 0, 22),
                $groupId,
            );
        }

        $sut = $this->get(CustomerGroupCountDaoInterface::class);

        $result = $sut->getCustomerGroupCounts();
        $this->assertSame($userCount, $result[0]->getCount());
    }

    #[Test]
    public function inactiveGroupsAreExcluded(): void
    {
        $this->deleteTableContent('oxobject2group');
        $this->deleteTableContent('oxgroups');

        $this->createGroup(
            '_tgrp' . substr(uniqid('', true), 0, 22),
            uniqid(),
            active: false,
        );

        $sut = $this->get(CustomerGroupCountDaoInterface::class);

        $this->assertSame([], $sut->getCustomerGroupCounts());
    }

    private function createGroup(
        string $id,
        string $title,
        bool $active = true,
    ): void {
        $qb = $this->get(QueryBuilderFactoryInterface::class)->create();
        $qb->insert('oxgroups')
            ->values([
                'oxid' => ':id',
                'oxtitle' => ':title',
                'oxactive' => ':active',
            ])
            ->setParameter('id', $id)
            ->setParameter('title', $title)
            ->setParameter('active', (int) $active)
            ->execute();
    }

    private function assignUserToGroup(
        string $objectId,
        string $groupId,
    ): void {
        $qb = $this->get(QueryBuilderFactoryInterface::class)->create();
        $qb->insert('oxobject2group')
            ->values([
                'oxid' => ':oxid',
                'oxobjectid' => ':objectid',
                'oxgroupsid' => ':groupid',
            ])
            ->setParameter('oxid', uniqid())
            ->setParameter('objectid', $objectId)
            ->setParameter('groupid', $groupId)
            ->execute();
    }

    private function deleteTableContent(string $table): void
    {
        $this->get(QueryBuilderFactoryInterface::class)
            ->create()
            ->delete($table)
            ->execute();
    }
}
