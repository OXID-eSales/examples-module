<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\CustomerInfo\Controller;

use OxidEsales\AuthComponent\Security\User\ApiUser;
use OxidEsales\ExamplesModule\ApiEntrypoint\CustomerInfo\Controller\CustomerInfoApiController;
use OxidEsales\ExamplesModule\Tests\Unit\ApiEntrypoint\ApiEntrypointTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CustomerInfoApiController::class)]
final class CustomerInfoApiControllerTest extends ApiEntrypointTestCase
{
    public function testGetAuthenticatedUserInfoReturnsUserIdAndEmail(): void
    {
        $oxid = uniqid('oxid_');
        $email = uniqid('user_');

        $user = new ApiUser(oxid: $oxid, username: $email, roles: []);

        $sut = $this->getSut();
        $response = $sut->getAuthenticatedUserInfo($user);
        $data = $this->decodeResponse($response);

        $this->assertSame($oxid, $data['userId']);
        $this->assertSame($email, $data['email']);
    }

    private function getSut(): CustomerInfoApiController
    {
        return new CustomerInfoApiController();
    }
}
