<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Greeting\Repository;

use OxidEsales\Eshop\Application\Model\User as EshopModelUser;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\Greeting\Repository\GreetingRepository;
use OxidEsales\ExamplesModule\Greeting\Repository\GreetingRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(GreetingRepository::class)]
class GreetingRepositoryTest extends IntegrationTestCase
{
    public const TEST_USER_ID = '_testuser';
    public const TEST_GREETING = 'Hi there';

    public function testGetSavedUserGreeting(): void
    {
        $this->prepareTestData();

        $repo = $this->get(GreetingRepositoryInterface::class);

        $this->assertSame(self::TEST_GREETING, $repo->getSavedUserGreeting(self::TEST_USER_ID));
        $this->assertSame('', $repo->getSavedUserGreeting('_notexisting'));
    }

    private function prepareTestData(): void
    {
        $this->cleanUpUsers();

        $user = oxNew(EshopModelUser::class);
        $user->assign(
            [
                'oxid'         => self::TEST_USER_ID,
                'oeemgreeting' => self::TEST_GREETING,
            ]
        );
        $user->save();
    }

    private function cleanUpUsers()
    {
        $queryBuilder = $this->get(QueryBuilderFactoryInterface::class)->create();
        $queryBuilder->delete('oxuser');
        $queryBuilder->execute();
    }
}
