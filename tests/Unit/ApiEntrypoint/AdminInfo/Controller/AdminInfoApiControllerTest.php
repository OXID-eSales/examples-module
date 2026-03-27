<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\AdminInfo\Controller;

use OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\Controller\AdminInfoApiController;
use OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\DataObject\AdminInfo;
use OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\Service\AdminInfoServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\InMemoryUser;

#[CoversClass(AdminInfoApiController::class)]
final class AdminInfoApiControllerTest extends TestCase
{
    public function testGetAdminInfoReturnsJsonResponse(): void
    {
        $sut = $this->getSutWithStubService(uniqid() . '@example.com');

        $this->assertInstanceOf(
            JsonResponse::class,
            $sut->getAdminInfo($this->createRequestWithUser(uniqid()))
        );
    }

    public function testGetAdminInfoReturnsEmailAndGreeting(): void
    {
        $email = uniqid('admin_', true) . '@example.com';
        $greeting = uniqid('greeting_', true);

        $serviceStub = $this->createStub(AdminInfoServiceInterface::class);
        $serviceStub->method('getAdminInfo')
            ->with($email)
            ->willReturn(new AdminInfo(
                email: $email,
                greeting: $greeting,
            ));

        $sut = $this->getSut(adminInfoService: $serviceStub);
        $data = $this->decodeResponse(
            $sut->getAdminInfo($this->createRequestWithUser($email))
        );

        $this->assertSame($email, $data['email']);
        $this->assertSame($greeting, $data['greeting']);
    }

    public function testGetAdminInfoReturnsStatus200(): void
    {
        $sut = $this->getSutWithStubService(uniqid() . '@example.com');

        $this->assertSame(
            200,
            $sut->getAdminInfo($this->createRequestWithUser(uniqid()))->getStatusCode()
        );
    }

    public function testGetAdminInfoResponseStructure(): void
    {
        $sut = $this->getSutWithStubService(uniqid() . '@example.com');

        $data = $this->decodeResponse(
            $sut->getAdminInfo($this->createRequestWithUser(uniqid()))
        );

        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('greeting', $data);
        $this->assertCount(2, $data);
    }

    private function getSutWithStubService(string $email): AdminInfoApiController
    {
        $serviceStub = $this->createStub(AdminInfoServiceInterface::class);
        $serviceStub->method('getAdminInfo')
            ->willReturn(new AdminInfo(
                email: $email,
                greeting: uniqid(),
            ));

        return $this->getSut(adminInfoService: $serviceStub);
    }

    private function getSut(
        ?AdminInfoServiceInterface $adminInfoService = null,
    ): AdminInfoApiController {
        return new AdminInfoApiController(
            adminInfoService: $adminInfoService
                ?? $this->createStub(AdminInfoServiceInterface::class),
        );
    }

    private function createRequestWithUser(string $username): Request
    {
        $request = new Request();
        $user = new InMemoryUser($username, null, ['ROLE_USER', 'ROLE_ADMIN']);
        $request->attributes->set('_user', $user);

        return $request;
    }

    private function decodeResponse(JsonResponse $response): array
    {
        return json_decode($response->getContent(), true);
    }
}
