<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Controller\Admin;

use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\ExamplesModule\Extension\Model\User as TemplateModelUser;
use OxidEsales\ExamplesModule\Greeting\Service\UserServiceInterface;

class GreetingAdminController extends AdminController
{
    protected $_sThisTemplate = '@oe_examples_module/admin/user_greetings';

    public function render()
    {
        $userService = $this->getService(UserServiceInterface::class);
        if ($this->getEditObjectId()) {
            /** @var TemplateModelUser $oUser */
            $oUser = $userService->getUserById($this->getEditObjectId());
            $this->addTplParam(ModuleCore::OEEM_ADMIN_GREETING_TEMPLATE_VARNAME, $oUser->getPersonalGreeting());
        }

        return parent::render();
    }
}
