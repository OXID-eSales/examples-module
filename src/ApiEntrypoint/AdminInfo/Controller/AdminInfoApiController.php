<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\Controller;

use OxidEsales\ExamplesModule\ApiEntrypoint\AdminInfo\Service\AdminInfoServiceInterface;
use OxidEsales\SessionAuthComponent\Security\Attribute\AdminSessionUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class AdminInfoApiController
{
    public function __construct(
        private AdminInfoServiceInterface $adminInfoService,
    ) {
    }

    #[Route('/api/admin-info', methods: ['GET'])]
    #[AdminSessionUser(roles: ['ROLE_ADMIN'])]
    public function getAdminInfo(Request $request): JsonResponse
    {
        /** @var UserInterface $user */
        $user = $request->attributes->get('_user');

        $adminInfo = $this->adminInfoService->getAdminInfo(
            $user->getUserIdentifier()
        );

        return new JsonResponse([
            'email' => $adminInfo->getEmail(),
            'greeting' => $adminInfo->getGreeting(),
        ]);
    }
}
