<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Codeception\Acceptance\Admin;

use OxidEsales\Codeception\Module\Translation\Translator;
use OxidEsales\ExamplesModule\Tests\Codeception\Support\AcceptanceTester;

/**
 * @group oe_examples_module
 * @group oe_examples_module_admin
 */
final class GreetingAdminCest
{
    public function _before(AcceptanceTester $I): void
    {
        //ensure each test start from same environment
        $I->setGreetingModeGeneric();
        $this->setUserPersonalGreeting($I, 'Hello there!');
    }

    public function _after(AcceptanceTester $I): void
    {
        //clean up after each test
        $I->setGreetingModeGeneric();
    }

    /** @param AcceptanceTester $I */
    public function seeGreetingOptionsForUser(AcceptanceTester $I): void
    {
        $I->openAdmin();
        $adminPage = $I->loginAdmin();

        $userList = $adminPage->openUsers();
        $userList->find("where[oxuser][oxusername]", $I->getDemoUserName());

        $I->selectEditFrame();
        $I->see(Translator::translate('OEEXAMPLESMODULE_ALLOW_GREETING'));

        $I->selectListFrame();
        $I->click(Translator::translate('tbcluser_greetings'));

        $I->selectEditFrame();
        $I->see(Translator::translate('OEEXAMPLESMODULE_GREETING_TITLE'));
        $I->see('Hello there!');
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
