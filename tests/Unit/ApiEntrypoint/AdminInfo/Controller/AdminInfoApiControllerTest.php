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

        $serviceStub = $this->createStub(AdminInfoServiceInterface::class);
        $serviceStub->method('getAdminInfo')
            ->with($email)
            ->willReturn(new AdminInfo(
                email: $email,
                greeting: $greeting,
            ));

        $sut = $this->getSut(adminInfoService: $serviceStub);

        $response = $sut->getAdminInfo($this->createRequestWithUser($email));
        $data = $this->decodeJsonResponse($response);

        $this->assertSame($email, $data['email']);
        $this->assertSame($greeting, $data['greeting']);
    }

    public function testGetAdminInfoResponseStructure(): void
    {
        $sut = $this->getSut();

        $response = $sut->getAdminInfo($this->createRequestWithUser(uniqid()));
        $data = $this->decodeJsonResponse($response);

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
                ->willReturn(new AdminInfo(
                    email: uniqid() . '@example.com',
                    greeting: uniqid(),
                ));
        }

        return new AdminInfoApiController(
            adminInfoService: $adminInfoService,
        );
    }

    private function createRequestWithUser(string $username): Request
    {
        $request = new Request();
        $user = new InMemoryUser($username, null, ['ROLE_USER', 'ROLE_ADMIN']);
        $request->attributes->set('_user', $user);

        return $request;
    }

    private function decodeJsonResponse(JsonResponse $response): array
    {
        return json_decode($response->getContent(), true);
    }
}
