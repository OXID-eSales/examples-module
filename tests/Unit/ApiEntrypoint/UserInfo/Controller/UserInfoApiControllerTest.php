<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\UserInfo\Controller;

use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Controller\UserInfoApiController;
use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\DataObject\UserInfo;
use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Service\UserInfoServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\InMemoryUser;

#[CoversClass(UserInfoApiController::class)]
final class UserInfoApiControllerTest extends TestCase
{
    public function testGetUserInfoReturnsJsonResponse(): void
    {
        $sut = $this->getSut();
        $request = $this->createRequestWithUser(uniqid());

        $this->assertInstanceOf(
            JsonResponse::class,
            $sut->getUserInfo($request)
        );
    }

    public function testGetUserInfoReturnsFirstNameAndGreetingUrl(): void
    {
        $username = uniqid('user_', true);
        $firstName = uniqid('name_', true);
        $greetingUrl = uniqid('url_', true);

        $serviceStub = $this->createStub(UserInfoServiceInterface::class);
        $serviceStub->method('getUserInfo')
            ->with($username)
            ->willReturn(new UserInfo(
                firstName: $firstName,
                greetingUrl: $greetingUrl,
            ));

        $sut = $this->getSut(userInfoService: $serviceStub);
        $request = $this->createRequestWithUser($username);

        $data = $this->decodeResponse($sut->getUserInfo($request));

        $this->assertSame($firstName, $data['firstName']);
        $this->assertSame($greetingUrl, $data['greetingUrl']);
    }

    public function testGetUserInfoReturnsStatus200(): void
    {
        $username = uniqid('user_', true);

        $serviceStub = $this->createStub(UserInfoServiceInterface::class);
        $serviceStub->method('getUserInfo')
            ->willReturn(new UserInfo(
                firstName: uniqid(),
                greetingUrl: uniqid(),
            ));

        $sut = $this->getSut(userInfoService: $serviceStub);
        $request = $this->createRequestWithUser($username);

        $this->assertSame(200, $sut->getUserInfo($request)->getStatusCode());
    }

    public function testGetUserInfoReturns404WhenUserNotFound(): void
    {
        $serviceStub = $this->createStub(UserInfoServiceInterface::class);
        $serviceStub->method('getUserInfo')
            ->willReturn(null);

        $sut = $this->getSut(userInfoService: $serviceStub);
        $request = $this->createRequestWithUser(uniqid());

        $response = $sut->getUserInfo($request);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testGetUserInfoResponseStructure(): void
    {
        $serviceStub = $this->createStub(UserInfoServiceInterface::class);
        $serviceStub->method('getUserInfo')
            ->willReturn(new UserInfo(
                firstName: uniqid(),
                greetingUrl: uniqid(),
            ));

        $sut = $this->getSut(userInfoService: $serviceStub);
        $request = $this->createRequestWithUser(uniqid());

        $data = $this->decodeResponse($sut->getUserInfo($request));

        $this->assertArrayHasKey('firstName', $data);
        $this->assertArrayHasKey('greetingUrl', $data);
        $this->assertCount(2, $data);
    }

    private function getSut(
        ?UserInfoServiceInterface $userInfoService = null,
    ): UserInfoApiController {
        return new UserInfoApiController(
            userInfoService: $userInfoService
                ?? $this->createStub(UserInfoServiceInterface::class),
        );
    }

    private function createRequestWithUser(string $username): Request
    {
        $request = new Request();
        $user = new InMemoryUser($username, null, ['ROLE_USER']);
        $request->attributes->set('_user', $user);

        return $request;
    }

    private function decodeResponse(JsonResponse $response): array
    {
        return json_decode($response->getContent(), true);
    }
}
