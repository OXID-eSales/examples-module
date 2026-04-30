<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Controller;

use OxidEsales\ExamplesModule\ApiEntrypoint\UserInfo\Service\UserInfoServiceInterface;
use OxidEsales\SessionAuthComponent\Security\Attribute\SessionUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class UserInfoApiController
{
    public function __construct(
        private UserInfoServiceInterface $userInfoService,
    ) {
    }

    #[Route('/api/user-info', methods: ['GET'])]
    #[SessionUser]
    public function getUserInfo(Request $request): JsonResponse
    {
        /** @var UserInterface $user */
        $user = $request->attributes->get('_user');

        $userInfo = $this->userInfoService->getUserInfo(
            $user->getUserIdentifier()
        );

        if ($userInfo === null) {
            return new JsonResponse(
                ['error' => 'User not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse([
            'firstName' => $userInfo->getFirstName(),
            'greetingUrl' => $userInfo->getGreetingUrl(),
        ]);
    }
}
