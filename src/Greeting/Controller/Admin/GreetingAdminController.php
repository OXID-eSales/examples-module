<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Controller\Admin;

use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\ExamplesModule\Extension\Model\User as ExamplesModelUser;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\UserRepositoryInterface;
use OxidEsales\ExamplesModule\Greeting\Transput\AdminGreetingRequestInterface;

class GreetingAdminController extends AdminController
{
    protected $_sThisTemplate = '@oe_examples_module/admin/user_greetings';

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AdminGreetingRequestInterface $request,
    ) {
        parent::__construct();
    }

    public function render()
    {
        if ($this->request->getEditObjectId()) {
            /** @var ExamplesModelUser $user */
            $user = $this->userRepository->getUserById($this->request->getEditObjectId());
            $this->addTplParam(ModuleCore::OEEM_ADMIN_GREETING_TEMPLATE_VARNAME, $user->getPersonalGreeting());
        }

        return parent::render();
    }
}
