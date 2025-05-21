<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Controller\Admin;

use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use OxidEsales\ExamplesModule\Greeting\Controller\Admin\GreetingAdminController;
use OxidEsales\ExamplesModule\Greeting\Model\PersonalGreetingUserInterface;
use OxidEsales\ExamplesModule\Greeting\Service\UserServiceInterface;
use OxidEsales\ExamplesModule\Greeting\Transput\AdminGreetingRequestInterface;
use OxidEsales\ExamplesModule\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\Test;

final class GreetingAdminControllerTest extends IntegrationTestCase
{
    #[Test]
    public function renderSetsTplParamIfEditObjectGivenByRequest(): void
    {
        $requestStub = $this->createConfiguredStub(AdminGreetingRequestInterface::class, [
            'getEditObjectId' => $userId = uniqid()
        ]);

        $userStub = $this->createConfiguredStub(PersonalGreetingUserInterface::class, [
            'getPersonalGreeting' => $expectedGreeting = uniqid(),
        ]);

        $userServiceMock = $this->createMock(UserServiceInterface::class);
        $userServiceMock->method('getUserById')
            ->with($userId)
            ->willReturn($userStub);

        $sut = $this->getSut(
            request: $requestStub,
            userService: $userServiceMock,
        );

        $this->assertSame('@oe_examples_module/admin/user_greetings', $sut->render());

        $paramValue = $sut->getViewDataElement(ModuleCore::OEEM_ADMIN_GREETING_TEMPLATE_VARNAME);
        $this->assertSame($expectedGreeting, $paramValue);
    }

    #[Test]
    public function renderDoesntSetTplParamIfEditObjectIsNotGivenByRequest(): void
    {
        $userServiceSpy = $this->createMock(UserServiceInterface::class);
        $userServiceSpy->expects($this->never())->method('getUserById');

        $sut = $this->getSut(
            userService: $userServiceSpy,
        );

        $this->assertSame('@oe_examples_module/admin/user_greetings', $sut->render());

        $paramValue = $sut->getViewDataElement(ModuleCore::OEEM_ADMIN_GREETING_TEMPLATE_VARNAME);
        $this->assertNull($paramValue);
    }

    private function getSut(
        ?UserServiceInterface $userService = null,
        ?AdminGreetingRequestInterface $request = null
    ): GreetingAdminController {
        $userService ??= $this->createStub(UserServiceInterface::class);
        $request ??= $this->createStub(AdminGreetingRequestInterface::class);
        return new GreetingAdminController(
            userService: $userService,
            request: $request,
        );
    }
}
