<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Codeception\Acceptance;

use OxidEsales\Codeception\Module\Translation\Translator;
use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use OxidEsales\ExamplesModule\Tests\Codeception\Support\AcceptanceTester;

/**
 * @group oe_examples_module
 * @group oe_examples_module_updategreeting
 */
final class UpdateGreetingCest
{
    public function _before(AcceptanceTester $I): void
    {
        //ensure each test start from same environment
        $I->setGreetingModePersonal();
        $I->resetGreetingTracker();
    }

    public function _after(AcceptanceTester $I): void
    {
        //clean up after each test
        $I->setGreetingModeGeneric();
        $this->setUserPersonalGreeting($I, '');
        $I->resetGreetingTracker();
    }

    public function testSetGreetingMode(AcceptanceTester $I): void
    {
        $I->wantToTest('user updates the personal greeting');

        $this->setUserPersonalGreeting($I, 'Hi there sweetie');

        $I->openShop()
            ->loginUser($I->getDemoUserName(), $I->getDemoUserPassword());

        $I->waitForText(sprintf(Translator::translate('OEEXAMPLESMODULE_GREETING'), getenv('OEEM_SHOP_NAME')));
        $I->dontSee(Translator::translate('OEEXAMPLESMODULE_GREETING_GENERIC'));
        $I->see('Hi there sweetie');

        $I->seeElement('#oeem_update_greeting');
        $I->click('#oeem_update_greeting');
        $I->see(Translator::translate('OEEXAMPLESMODULE_GREETING_UPDATE_TITLE'));
        $I->see(Translator::translate('OEEXAMPLESMODULE_GREETING_UPDATE_COUNT') . '0');

        $I->seeElement('#oeemgreeting_submit');
        $I->seeElement('#oeem_greeting_input');
        $I->fillField(ModuleCore::OEEM_GREETING_TEMPLATE_VARNAME, 'Hello master of the filled cart');
        $I->click('#oeemgreeting_submit');
        $I->see(Translator::translate('OEEXAMPLESMODULE_GREETING_UPDATE_COUNT') . '1');

        //See changed greeting text on start page
        $I->openShop();
        $I->see('Hello master of the filled cart');
    }

    public function testTrackGreetingModeChanges(AcceptanceTester $I): void
    {
        $I->wantToTest('track how often user updated the personal greeting');

        $this->setUserPersonalGreeting($I, 'Hi there sweetie');

        $I->openShop()
            ->loginUser($I->getDemoUserName(), $I->getDemoUserPassword());

        $I->seeElement('#oeem_update_greeting');
        $I->click('#oeem_update_greeting');
        $I->see(Translator::translate('OEEXAMPLESMODULE_GREETING_UPDATE_COUNT') . '0');

        $I->seeElement('#oeemgreeting_submit');
        $I->fillField(ModuleCore::OEEM_GREETING_TEMPLATE_VARNAME, 'Hello master of the filled cart');
        $I->click('#oeemgreeting_submit');
        $I->see(Translator::translate('OEEXAMPLESMODULE_GREETING_UPDATE_COUNT') . '1');

        $I->fillField(ModuleCore::OEEM_GREETING_TEMPLATE_VARNAME, 'Hi shopping addict');
        $I->click('#oeemgreeting_submit');
        $I->see(Translator::translate('OEEXAMPLESMODULE_GREETING_UPDATE_COUNT') . '2');

        //trying to change to same does not update the count
        $I->click('#oeemgreeting_submit');
        $I->see(Translator::translate('OEEXAMPLESMODULE_GREETING_UPDATE_COUNT') . '2');

        //See changed greeting text on start page
        $I->openShop();
        $I->see('Hi shopping addict');
    }

    /**
     * This is an edge case test.
     *
     * An anonymous (not logged in) user will not get the direct access button to
     * the module controller on the start page.
     * Which does not mean that he has no access to that controller at all.
     * A controller/action can always be accessed directly, so we need to ensure
     * it is 'kindersicher'.
     */
    public function testAnonymousUserAccessingModuleController(AcceptanceTester $I): void
    {
        $I->wantToTest('not logged in user accessing module controller');

        $I->openShop();
        $I->amOnUrl($I->getShopUrl() . '?cl=oeem_greeting');

        $I->see(Translator::translate('OEEXAMPLESMODULE_GREETING_UPDATE_COUNT') . '0');
        $I->fillField(ModuleCore::OEEM_GREETING_TEMPLATE_VARNAME, 'Hi shopping addict');
        $I->click('#oeemgreeting_submit');

        // no harm done (do nothing)
        $I->see(Translator::translate('OEEXAMPLESMODULE_GREETING_UPDATE_COUNT') . '0');
        $I->assertEmpty($I->grabValueFrom(ModuleCore::OEEM_GREETING_TEMPLATE_VARNAME));

        // NOTE: in real life, you could show some 'keep your fingers away from this' message
        // or redirect to shop start page.
    }

    private function setUserPersonalGreeting(AcceptanceTester $I, string $value = ''): void
    {
        $I->updateInDatabase(
            'oxuser',
            [
                'oeemgreeting' => $value,
            ],
            [
                'oxusername' => $I->getDemoUserName(),
            ]
        );
    }
}
