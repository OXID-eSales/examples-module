<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\ApiEntrypoint\CustomerInfo\Controller;

use OxidEsales\AuthComponent\Security\User\ApiUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

readonly class CustomerInfoApiController
{
    #[Route('/api/customer-info', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getAuthenticatedUserInfo(
        #[CurrentUser] ApiUser $user
    ): JsonResponse {
        return new JsonResponse([
            'userId' => $user->getOxid(),
            'email' => $user->getUserIdentifier(),
        ]);
    }
}
