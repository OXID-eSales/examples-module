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
    public function testGetUserInfoReturnsJsonResponseWithStatus200AndData(): void
    {
        $username = uniqid('user_');
        $firstName = uniqid('name_');
        $greetingUrl = uniqid('url_');

        $serviceStub = $this->createStub(UserInfoServiceInterface::class);
        $serviceStub->method('getUserInfo')
            ->with($username)
            ->willReturn(new UserInfo(firstName: $firstName, greetingUrl: $greetingUrl));

        $sut = $this->getSut(userInfoService: $serviceStub);
        $response = $sut->getUserInfo($this->createRequestWithUser($username));
        $data = $this->decodeResponse($response);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($firstName, $data['firstName']);
        $this->assertSame($greetingUrl, $data['greetingUrl']);
    }

    public function testGetUserInfoReturns404WhenUserNotFound(): void
    {
        $username = uniqid('user_');

        $serviceStub = $this->createStub(UserInfoServiceInterface::class);
        $serviceStub->method('getUserInfo')
            ->with($username)
            ->willReturn(null);

        $sut = $this->getSut(userInfoService: $serviceStub);
        $request = $this->createRequestWithUser($username);

        $response = $sut->getUserInfo($request);

        $this->assertSame(404, $response->getStatusCode());
    }

    private function getSut(
        ?UserInfoServiceInterface $userInfoService = null,
    ): UserInfoApiController {
        $userInfoService ??= $this->createStub(UserInfoServiceInterface::class);

        return new UserInfoApiController(
            userInfoService: $userInfoService,
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
