<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Greeting\Infrastructure\Repository;

use OxidEsales\Eshop\Application\Model\User as EshopModelUser;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\Extension\Model\User;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\GreetingRepository;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\GreetingRepositoryInterface;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\UserRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function activeUserGreetingCanBeUpdated(): void
    {
        $this->prepareTestData();

        $activeUser = oxNew(User::class);
        $activeUser->load(self::TEST_USER_ID);

        $userRepositoryStub = $this->createConfiguredStub(UserRepositoryInterface::class, [
            'getActiveUser' => $activeUser
        ]);

        $sut = new GreetingRepository(
            queryBuilderFactory: $this->get(QueryBuilderFactoryInterface::class),
            userRepository: $userRepositoryStub,
        );

        $greetingExample = uniqid('greeting_');
        $sut->saveGreetingForActiveUser($greetingExample);

        // check that in-memory user object was updated
        $this->assertSame($greetingExample, $activeUser->getPersonalGreeting());

        // check that greeting was persisted
        $this->assertSame($greetingExample, $sut->getSavedUserGreeting($activeUser->getId()));
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
