<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Codeception\Acceptance;

use Codeception\Attribute\Group;
use Codeception\Util\Fixtures;
use OxidEsales\ExamplesModule\Tests\Codeception\Support\AcceptanceTester;

#[Group('oe_examples_module')]
#[Group('oe_examples_module_api')]
final class AdminInfoApiCest
{
    public function testAdminHeaderShowsGreetingWithEmail(
        AcceptanceTester $I
    ): void {
        $I->wantToTest(
            'admin header shows greeting with admin email'
        );

        $admin = Fixtures::get('adminUser');

        $I->loginAdmin();
        $I->selectHeaderFrame();
        $I->waitForElementVisible('#oeem-admin-greeting', 10);

        $greetingText = $I->grabTextFrom('#oeem-admin-greeting');
        $I->assertStringContainsString($admin['email'], $greetingText);
    }

    public function testAdminGreetingContainsAdminPrefix(
        AcceptanceTester $I
    ): void {
        $I->wantToTest(
            'admin greeting contains Admin prefix'
        );

        $I->loginAdmin();
        $I->selectHeaderFrame();
        $I->waitForElementVisible('#oeem-admin-greeting', 10);

        $greetingText = $I->grabTextFrom('#oeem-admin-greeting');
        $I->assertStringContainsString('Admin', $greetingText);
    }

    public function testAdminInfoApiRejectsUnauthenticated(
        AcceptanceTester $I
    ): void {
        $I->wantToTest(
            'admin-info endpoint rejects unauthenticated requests'
        );

        $I->openShop();
        $I->waitForPageLoad();

        $response = $I->executeAsyncJS(
            "var callback = arguments[arguments.length - 1];"
            . "fetch('/api/admin-info')"
            . ".then(function(r) { callback({status: r.status}); })"
            . ".catch(function(e) { callback({status: 0}); });"
        );

        $I->assertSame(401, $response['status']);
    }
}
