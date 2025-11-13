<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Controller\Admin;

use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use OxidEsales\ExamplesModule\Greeting\Controller\Admin\GreetingAdminController;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\UserRepositoryInterface;
use OxidEsales\ExamplesModule\Greeting\Model\PersonalGreetingUserInterface;
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

        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $userRepositoryMock->method('getUserById')
            ->with($userId)
            ->willReturn($userStub);

        $sut = $this->getSut(
            request: $requestStub,
            userRepository: $userRepositoryMock,
        );

        $this->assertSame('@oe_examples_module/admin/user_greetings', $sut->render());

        $paramValue = $sut->getViewDataElement(ModuleCore::OEEM_ADMIN_GREETING_TEMPLATE_VARNAME);
        $this->assertSame($expectedGreeting, $paramValue);
    }

    #[Test]
    public function renderDoesntSetTplParamIfEditObjectIsNotGivenByRequest(): void
    {
        $userRepositorySpy = $this->createMock(UserRepositoryInterface::class);
        $userRepositorySpy->expects($this->never())->method('getUserById');

        $sut = $this->getSut(
            userRepository: $userRepositorySpy,
        );

        $this->assertSame('@oe_examples_module/admin/user_greetings', $sut->render());

        $paramValue = $sut->getViewDataElement(ModuleCore::OEEM_ADMIN_GREETING_TEMPLATE_VARNAME);
        $this->assertNull($paramValue);
    }

    private function getSut(
        ?UserRepositoryInterface $userRepository = null,
        ?AdminGreetingRequestInterface $request = null
    ): GreetingAdminController {
        $userRepository ??= $this->createStub(UserRepositoryInterface::class);
        $request ??= $this->createStub(AdminGreetingRequestInterface::class);
        return new GreetingAdminController(
            userRepository: $userRepository,
            request: $request,
        );
    }
}
