<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Codeception\Acceptance;

use Codeception\Attribute\Group;
use OxidEsales\ExamplesModule\Tests\Codeception\Support\AcceptanceTester;

#[Group('oe_examples_module')]
#[Group('oe_examples_module_api')]
final class ProductInfoApiCest
{
    public function testProductInfoReturnsJsonWithProductCount(
        AcceptanceTester $I
    ): void {
        $I->wantToTest('public product-info endpoint returns JSON');

        $I->openShop();
        $I->waitForPageLoad();

        $response = $this->fetchApi($I, '/api/product-info');

        $I->assertSame(200, $response['status']);
        $I->assertArrayHasKey('productCount', $response['body']);
        $I->assertArrayHasKey('message', $response['body']);
    }

    public function testProductInfoProductCountIsInteger(
        AcceptanceTester $I
    ): void {
        $I->wantToTest('product count is an integer');

        $I->openShop();
        $I->waitForPageLoad();

        $response = $this->fetchApi($I, '/api/product-info');

        $I->assertIsInt($response['body']['productCount']);
        $I->assertGreaterThanOrEqual(0, $response['body']['productCount']);
    }

    public function testProductInfoContainsTranslatedMessage(
        AcceptanceTester $I
    ): void {
        $I->wantToTest('response contains a non-empty translated message');

        $I->openShop();
        $I->waitForPageLoad();

        $response = $this->fetchApi($I, '/api/product-info');

        $I->assertNotEmpty($response['body']['message']);
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
}
