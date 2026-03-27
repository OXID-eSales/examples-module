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
final class CustomerGroupApiCest
{
    public function testCustomerGroupsRejectsUnauthenticatedRequest(
        AcceptanceTester $I
    ): void {
        $I->wantToTest('customer-groups rejects unauthenticated requests');

        $I->openShop();
        $I->waitForPageLoad();

        $response = $this->fetchApi($I, '/api/customer-groups');

        $I->assertSame(401, $response['status']);
    }

    public function testCustomerGroupsReturnsDataWithAdminToken(
        AcceptanceTester $I
    ): void {
        $I->wantToTest('customer-groups returns data for admin');

        $I->openShop();
        $I->waitForPageLoad();

        $admin = Fixtures::get('adminUser');
        $token = $this->loginViaApi($I, $admin['email'], $admin['password']);

        $response = $this->fetchApiWithToken(
            $I,
            '/api/customer-groups',
            $token
        );

        $I->assertSame(200, $response['status']);
        $I->assertArrayHasKey('customerGroups', $response['body']);
        $I->assertArrayHasKey('total', $response['body']);
    }

    public function testCustomerGroupsContainsGroupStructure(
        AcceptanceTester $I
    ): void {
        $I->wantToTest('customer group entries have correct structure');

        $I->openShop();
        $I->waitForPageLoad();

        $admin = Fixtures::get('adminUser');
        $token = $this->loginViaApi($I, $admin['email'], $admin['password']);

        $response = $this->fetchApiWithToken(
            $I,
            '/api/customer-groups',
            $token
        );

        $I->assertNotEmpty($response['body']['customerGroups']);
        $group = $response['body']['customerGroups'][0];
        $I->assertArrayHasKey('groupId', $group);
        $I->assertArrayHasKey('title', $group);
        $I->assertArrayHasKey('count', $group);
    }

    private function loginViaApi(
        AcceptanceTester $I,
        string $username,
        string $password
    ): string {
        $result = $I->executeAsyncJS(
            "var callback = arguments[arguments.length - 1];"
            . "fetch('/api/login', {"
            . "  method: 'POST',"
            . "  headers: {'Content-Type': 'application/json'},"
            . "  body: JSON.stringify({"
            . "    username: '" . $username . "',"
            . "    password: '" . $password . "'"
            . "  })"
            . "})"
            . ".then(function(r) { return r.json(); })"
            . ".then(function(b) { callback(b); })"
            . ".catch(function(e) { callback({error: e.message}); });"
        );

        return $result['token'];
    }

    private function fetchApi(AcceptanceTester $I, string $path): array
    {
        return $I->executeAsyncJS(
            "var callback = arguments[arguments.length - 1];"
            . "fetch('" . $path . "')"
            . ".then(function(r) {"
            . "  var status = r.status;"
            . "  return r.json().then(function(b) {"
            . "    callback({status: status, body: b});"
            . "  });"
            . "})"
            . ".catch(function(e) {"
            . "  callback({status: 0, body: {error: e.message}});"
            . "});"
        );
    }

    private function fetchApiWithToken(
        AcceptanceTester $I,
        string $path,
        string $token
    ): array {
        return $I->executeAsyncJS(
            "var callback = arguments[arguments.length - 1];"
            . "fetch('" . $path . "', {"
            . "  headers: {'Authorization': 'Bearer " . $token . "'}"
            . "})"
            . ".then(function(r) {"
            . "  var status = r.status;"
            . "  return r.json().then(function(b) {"
            . "    callback({status: status, body: b});"
            . "  });"
            . "})"
            . ".catch(function(e) {"
            . "  callback({status: 0, body: {error: e.message}});"
            . "});"
        );
    }
}
