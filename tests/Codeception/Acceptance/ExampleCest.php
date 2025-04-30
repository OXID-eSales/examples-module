<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Codeception\Acceptance;

use OxidEsales\Codeception\Module\Translation\Translator;
use OxidEsales\ExamplesModule\Tests\Codeception\Support\AcceptanceTester;

/**
 * @group oe_examples_module
 * @group oe_examples_module_startpage
 */
final class ExampleCest
{
    public function testCanOpenShopStartPage(AcceptanceTester $I): void
    {
        $I->wantToTest('that codeception tests are working');

        $I->openShop();
        $I->waitForPageLoad();

        $I->see(Translator::translate('HOME'));
    }
}
