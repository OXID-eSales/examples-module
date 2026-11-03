<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\UserInfo\Controller;

use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Controller\UserInfoApiController;
use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\DTO\UserInfoInterface;
use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Service\UserInfoServiceInterface;
use OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\ApiEntrypointTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\JsonResponse;

final class UserInfoApiControllerTest extends ApiEntrypointTestCase
{
    #[Test]
    public function getUserInfoReturnsJsonResponseWithStatus200AndData(): void
    {
        $username = uniqid('user_');
        $firstName = uniqid('name_');
        $greetingUrl = uniqid('url_');

        $userInfoStub = $this->createConfiguredStub(UserInfoInterface::class, [
            'getFirstName' => $firstName,
            'getGreetingUrl' => $greetingUrl,
        ]);

        $serviceMock = $this->createMock(UserInfoServiceInterface::class);
        $serviceMock->expects($this->once())
            ->method('getUserInfo')
            ->with($username)
            ->willReturn($userInfoStub);

        $sut = $this->getSut(
            userInfoService: $serviceMock,
        );
        $response = $sut->getUserInfo($this->createRequestWithUser($username));
        $data = $this->decodeResponse($response);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($firstName, $data['firstName']);
        $this->assertSame($greetingUrl, $data['greetingUrl']);
    }

    #[Test]
    public function getUserInfoReturns404WhenUserNotFound(): void
    {
        $username = uniqid('user_');

        $serviceMock = $this->createMock(UserInfoServiceInterface::class);
        $serviceMock->expects($this->once())
            ->method('getUserInfo')
            ->with($username)
            ->willReturn(null);

        $sut = $this->getSut(
            userInfoService: $serviceMock,
        );
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
}
