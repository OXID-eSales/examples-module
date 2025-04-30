<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Controller\Admin;

use OxidEsales\Eshop\Application\Model\User as EshopModelUser;
use OxidEsales\ExamplesModule\Greeting\Controller\Admin\GreetingAdminController;
use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use OxidEsales\ExamplesModule\Tests\Integration\IntegrationTestCase;

/*
 * We want to test controller behavior going 'full way'.
 * No mocks, we go straight to the database (full integration)).
 */
final class GreetingAdminControllerTest extends IntegrationTestCase
{
    public const TEST_USER_ID = '_testuser';

    public const TEST_GREETING = 'Hello there!';

    public function testRender(): void
    {
        $this->createTestUser();

        $controller = oxNew(GreetingAdminController::class);
        $controller->setEditObjectId(self::TEST_USER_ID);

        $this->assertSame('@oe_examples_module/admin/user_greetings', $controller->render());

        $viewData = $controller->getViewData();

        $this->assertSame(self::TEST_GREETING, $viewData[ModuleCore::OEEM_ADMIN_GREETING_TEMPLATE_VARNAME]);
    }

    private function createTestUser(): void
    {
        $user = oxNew(EshopModelUser::class);
        $user->assign(
            [
                'oxid'         => self::TEST_USER_ID,
                'oeemgreeting' => self::TEST_GREETING,
            ]
        );
        $user->save();
    }
}
