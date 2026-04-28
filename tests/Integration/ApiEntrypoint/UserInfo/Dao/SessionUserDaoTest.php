<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\ApiEntrypoint\UserInfo\Dao;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Dao\SessionUserDao;
use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Dao\SessionUserDaoInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(SessionUserDao::class)]
final class SessionUserDaoTest extends IntegrationTestCase
{
    #[Test]
    public function returnsFirstNameByUsername(): void
    {
        $username = uniqid('user_', true) . '@example.com';
        $firstName = uniqid('name_', true);

        $this->createUser(username: $username, firstName: $firstName);

        $sut = $this->get(SessionUserDaoInterface::class);

        $this->assertSame($firstName, $sut->getFirstNameByUsername($username));
    }

    #[Test]
    public function returnsNullForNonExistentUser(): void
    {
        $sut = $this->get(SessionUserDaoInterface::class);

        $this->assertNull(
            $sut->getFirstNameByUsername(uniqid('unknown_', true) . '@example.com')
        );
    }

    #[Test]
    public function returnsEmptyStringWhenFirstNameNotSet(): void
    {
        $username = uniqid('user_', true) . '@example.com';

        $this->createUser(username: $username, firstName: '');

        $sut = $this->get(SessionUserDaoInterface::class);

        $this->assertSame('', $sut->getFirstNameByUsername($username));
    }

    private function createUser(string $username, string $firstName): void
    {
        $user = oxNew(User::class);
        $user->setId('_tusr' . substr(uniqid('', true), 0, 22));
        $user->assign([
            'oxusername' => $username,
            'oxfname' => $firstName,
            'oxshopid' => 1,
        ]);
        $user->save();
    }
}
