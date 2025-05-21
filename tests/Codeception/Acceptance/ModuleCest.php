<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Codeception\Acceptance;

use OxidEsales\Codeception\Module\Translation\Translator;
use OxidEsales\ExamplesModule\Core\Module;
use OxidEsales\ExamplesModule\Tests\Codeception\Support\AcceptanceTester;

/**
 * @group oe_examples_module
 * @group oe_examples_module_module
 */
final class ModuleCest
{
    public function testCanDeactivateModule(AcceptanceTester $I): void
    {
        $I->wantToTest('that deactivating the module does not destroy the shop');

        $I->openShop();
        $I->waitForText(sprintf(Translator::translate('OEEXAMPLESMODULE_GREETING'), getenv('OEEM_SHOP_NAME')));

        $I->deactivateModule(Module::MODULE_ID);
        $I->reloadPage();

        $I->waitForPageLoad();
        $I->dontSee(sprintf(Translator::translate('OEEXAMPLESMODULE_GREETING'), getenv('OEEM_SHOP_NAME')));
    }
}
