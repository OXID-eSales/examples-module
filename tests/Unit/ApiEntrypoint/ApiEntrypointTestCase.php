<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\InMemoryUser;

abstract class ApiEntrypointTestCase extends TestCase
{
    protected function createRequestWithUser(string $username): Request
    {
        $request = new Request();
        $user = new InMemoryUser($username, null, []);
        $request->attributes->set('_user', $user);

        return $request;
    }

    protected function decodeResponse(JsonResponse $response): array
    {
        return json_decode($response->getContent(), true);
    }
}
