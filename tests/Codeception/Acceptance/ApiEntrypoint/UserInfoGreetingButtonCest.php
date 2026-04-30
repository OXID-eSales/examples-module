<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Codeception\Acceptance\ApiEntrypoint;

use Codeception\Attribute\Group;
use Codeception\Util\Fixtures;
use OxidEsales\Codeception\Step\Start as StartStep;
use OxidEsales\ExamplesModule\Tests\Codeception\Support\AcceptanceTester;

#[Group('oe_examples_module')]
#[Group('oe_examples_module_api')]
final class UserInfoGreetingButtonCest
{
    private const TEST_FIRST_NAME = 'TestUser';

    public function _before(AcceptanceTester $I): void
    {
        $user = Fixtures::get('user');
        $I->updateInDatabase(
            'oxuser',
            ['oxfname' => self::TEST_FIRST_NAME],
            ['oxusername' => $user['email']]
        );
    }

    public function _after(AcceptanceTester $I): void
    {
        $user = Fixtures::get('user');
        $I->updateInDatabase(
            'oxuser',
            ['oxfname' => ''],
            ['oxusername' => $user['email']]
        );
    }

    public function testGreetingButtonNotVisibleForAnonymousUser(
        AcceptanceTester $I
    ): void {
        $I->wantToTest(
            'greeting button is hidden for anonymous users'
        );

        $I->openShop();
        $I->waitForPageLoad();

        $I->dontSeeElement('#oeem-greeting-btn');
    }

    public function testGreetingButtonShowsFirstNameForLoggedInUser(
        AcceptanceTester $I
    ): void {
        $I->wantToTest(
            'greeting button shows user first name when logged in'
        );

        $startStep = new StartStep($I);
        $startStep->loginOnStartPage(
            $I->getDemoUserName(),
            $I->getDemoUserPassword()
        );

        $I->waitForPageLoad();
        $I->waitForElementVisible('#oeem-greeting-btn', 10);
        $I->seeElement('#oeem-greeting-btn');

        $buttonText = $I->grabTextFrom('#oeem-greeting-btn');
        $I->assertSame(self::TEST_FIRST_NAME, $buttonText);
    }

    public function testGreetingButtonLinksToGreetingController(
        AcceptanceTester $I
    ): void {
        $I->wantToTest(
            'greeting button links to the greeting controller'
        );

        $startStep = new StartStep($I);
        $startStep->loginOnStartPage(
            $I->getDemoUserName(),
            $I->getDemoUserPassword()
        );

        $I->waitForPageLoad();
        $I->waitForElementVisible('#oeem-greeting-btn', 10);
        $I->click('#oeem-greeting-btn');
        $I->waitForPageLoad();

        $I->seeInCurrentUrl('oeem_greeting');
    }
}
