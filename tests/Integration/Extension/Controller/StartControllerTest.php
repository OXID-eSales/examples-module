<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Extension\Controller;

use OxidEsales\Eshop\Application\Controller\StartController as EshopStartController;
use OxidEsales\Eshop\Application\Model\User as EshopModelUser;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Registry as EshopRegistry;
use OxidEsales\ExamplesModule\Core\Module;
use OxidEsales\ExamplesModule\Extension\Controller\StartController;
use OxidEsales\ExamplesModule\Settings\Service\ModuleSettingsServiceInterface;
use OxidEsales\ExamplesModule\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/*
 * Here we have full integration test cases for a what we call 'chain extended' shop class.
 * Current module might not be the only one extending the same class/method.
 * Always use the unified namespace name of the class instantiated with oxNew()
 * when testing.
 *
 * @todo: rework this fully to test only controller logic. probably, together with method extraction.
 */
#[CoversClass(StartController::class)]
final class StartControllerTest extends IntegrationTestCase
{
    public const TEST_USER_ID = '_testuser';

    public const TEST_GREETING = 'oh dear';

    public function setUp(): void
    {
        parent::setUp();

        $this->cleanUpUsers();
    }

    public function tearDown(): void
    {
        Registry::getSession()->setUser(null);
        parent::tearDown();
    }

    public function testGetGeneralGreeting(): void
    {
        $controller = oxNew(EshopStartController::class);

        $greetingPattern = EshopRegistry::getLang()->translateString(Module::GENERAL_GREETING_LANGUAGE_CONST);
        $expectedGreeting = sprintf($greetingPattern, getenv('OEEM_SHOP_NAME'));

        $this->assertSame($expectedGreeting, $controller->getOeemGeneralGreeting());
    }

    public function testShowGeneralGreeting(): void
    {
        $controller = oxNew(EshopStartController::class);
        $this->assertTrue($controller->showOeemGeneralGreeting());
    }

    /**
     * @dataProvider providerCanUpdateOeemGreeting
     */
    public function testCanUpdateOeemGreeting(bool $hasUser, string $mode, bool $expected): void
    {
        $moduleSettings = $this->get(ModuleSettingsServiceInterface::class);
        $moduleSettings->saveGreetingMode($mode);

        $controller = oxNew(EshopStartController::class);

        if ($hasUser) {
            $controller->setUser($this->getTestUser());
        }

        $this->assertEquals($expected, $controller->canUpdateOeemGreeting());
    }

    /**
     * @dataProvider providerGetOeemGreeting
     *
     * @param mixed $expect
     */
    public function testGetOeemGreeting(bool $hasUser, string $mode, $expect): void
    {
        $moduleSettings = $this->get(ModuleSettingsServiceInterface::class);
        $moduleSettings->saveGreetingMode($mode);

        $controller = oxNew(EshopStartController::class);

        if ($hasUser) {
            $controller->setUser($this->getTestUser());
        }

        $this->assertSame(
            (string)EshopRegistry::getLang()->translateString($expect),
            $controller->getOeemGreeting()
        );
    }

    public static function providerCanUpdateOeemGreeting(): array
    {
        return [
            'without_user_generic' => [
                'hasUser' => false,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_GENERIC,
                'expected' => false,
            ],
            'without_user_personal' => [
                'hasUser' => false,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_PERSONAL,
                'expected' => false,
            ],
            'with_user_generic' => [
                'hasUser' => true,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_GENERIC,
                'expected' => false,
            ],
            'with_user_personal' => [
                'hasUser' => true,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_PERSONAL,
                'expected' => true,
            ],
        ];
    }

    public static function providerGetOeemGreeting(): array
    {
        return [
            'without_user_generic' => [
                'hasUser' => false,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_GENERIC,
                'expect' => 'OEEXAMPLESMODULE_GREETING_GENERIC',
            ],
            'without_user_personal' => [
                'hasUser' => false,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_PERSONAL,
                'expect' => '',
            ],
            'with_user_generic' => [
                'hasUser' => true,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_GENERIC,
                'expect' => 'OEEXAMPLESMODULE_GREETING_GENERIC',
            ],
            'with_user_personal' => [
                'hasUser' => true,
                'mode' => ModuleSettingsServiceInterface::GREETING_MODE_PERSONAL,
                'expect' => self::TEST_GREETING,
            ],
        ];
    }

    private function getTestUser(): EshopModelUser
    {
        $user = oxNew(EshopModelUser::class);
        $user->assign(
            [
                'oxid' => self::TEST_USER_ID,
                'oeemgreeting' => self::TEST_GREETING,
            ]
        );
        $user->save();

        return $user;
    }
}
