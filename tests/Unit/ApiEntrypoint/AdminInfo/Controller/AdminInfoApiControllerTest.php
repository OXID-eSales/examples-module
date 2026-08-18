<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\AdminInfo\Controller;

use OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\Controller\AdminInfoApiController;
use OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\DTO\AdminInfoInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\Service\AdminInfoServiceInterface;
use OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\ApiEntrypointTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\JsonResponse;

#[CoversClass(AdminInfoApiController::class)]
final class AdminInfoApiControllerTest extends ApiEntrypointTestCase
{
    public function testGetAdminInfoReturnsJsonResponseWithStatus200(): void
    {
        $sut = $this->getSut();
        $response = $sut->getAdminInfo($this->createRequestWithUser(uniqid()));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testGetAdminInfoReturnsEmailAndGreeting(): void
    {
        $email = uniqid('admin_') . '@example.com';
        $greeting = uniqid('greeting_');

        $adminInfoStub = $this->createConfiguredStub(AdminInfoInterface::class, [
            'getEmail' => $email,
            'getGreeting' => $greeting,
        ]);
        $serviceMock = $this->createMock(AdminInfoServiceInterface::class);
        $serviceMock->expects($this->once())
            ->method('getAdminInfo')
            ->with($email)
            ->willReturn($adminInfoStub);

        $sut = $this->getSut(adminInfoService: $serviceMock);

        $response = $sut->getAdminInfo($this->createRequestWithUser($email));
        $data = $this->decodeResponse($response);

        $this->assertSame($email, $data['email']);
        $this->assertSame($greeting, $data['greeting']);
    }

    public function testGetAdminInfoResponseStructure(): void
    {
        $sut = $this->getSut();

        $response = $sut->getAdminInfo($this->createRequestWithUser(uniqid()));
        $data = $this->decodeResponse($response);

        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('greeting', $data);
        $this->assertCount(2, $data);
    }

    private function getSut(
        ?AdminInfoServiceInterface $adminInfoService = null,
    ): AdminInfoApiController {
        if ($adminInfoService === null) {
            $adminInfoService = $this->createStub(AdminInfoServiceInterface::class);
            $adminInfoService->method('getAdminInfo')
                ->willReturn($this->createStub(AdminInfoInterface::class));
        }

        return new AdminInfoApiController(
            adminInfoService: $adminInfoService,
        );
    }
}
