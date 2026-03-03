<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Codeception\Acceptance;

use OxidEsales\ExamplesModule\Tests\Codeception\Support\AcceptanceTester;

/**
 * @group oe_examples_module
 * @group oe_examples_module_summernote_extension
 */
final class SummernoteExtensionCest
{
    private const string TOOLBAR = '//div[contains(@class,"note-toolbar")]';
    private const string FORMATTING = self::TOOLBAR . '//div[contains(@class,"note-formatting")]';
    private const string FONT = self::TOOLBAR . '//div[contains(@class,"note-font")]';
    private const string MISC = self::TOOLBAR . '//div[contains(@class,"note-misc")]';
    private const string DROPDOWN = self::FONT . '//div[contains(@class,"dropdown-fontname")]';

    public function testModuleCanOverrideSummernoteOptions(AcceptanceTester $I): void
    {
        $I->wantToTest('Module can override Summernote toolbar and font names');

        $adminPanel = $I->loginAdmin();
        $adminPanel->openProducts();
        $I->selectEditFrame();

        $I->waitForElement('.note-editor', 15);
        $I->wait(3);

        // toolbar groups
        $I->seeNumberOfElements(self::TOOLBAR . '/div[contains(@class,"note-btn-group")]', 4);

        // formatting
        $I->seeElement(self::FORMATTING . '//button[contains(@class,"note-btn-bold")]');
        $I->seeElement(self::FORMATTING . '//button[contains(@class,"note-btn-italic")]');
        $I->seeNumberOfElements(self::FORMATTING . '//button[contains(@class,"note-btn")]', 2);

        // font
        $I->seeElement(self::FONT . '//*[@aria-label="Font Family"]');
        $I->seeElement(self::FONT . '//*[@aria-label="Font Size"]');
        $I->seeNumberOfElements(self::FONT . '//button[contains(@class,"note-btn")]', 2);

        // misc
        $I->seeElement(self::MISC . '//*[@aria-label="Cleaner"]');
        $I->seeElement(self::MISC . '//*[@aria-label="Print"]');
        $I->seeElement(self::MISC . '//button[contains(@class,"btn-codeview")]');
        $I->seeNumberOfElements(self::MISC . '//button[contains(@class,"note-btn")]', 3);

        // font names dropdown
        $I->click(self::FONT . '//button[@aria-label="Font Family"]');
        $I->wait(1);

        $I->seeNumberOfElements(self::DROPDOWN . '//a[contains(@class,"dropdown-item")]', 3);
        $I->see('Arial', self::DROPDOWN);
        $I->see('Courier New', self::DROPDOWN);
        $I->see('Custom Font', self::DROPDOWN);
    }
}
