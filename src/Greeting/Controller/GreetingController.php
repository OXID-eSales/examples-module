<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Controller;

use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use OxidEsales\ExamplesModule\Extension\Model\UserInterface;
use OxidEsales\ExamplesModule\Greeting\Exception\UserNotLoggedIn;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\UserRepositoryInterface;
use OxidEsales\ExamplesModule\Greeting\Service\GreetingMessageServiceInterface;
use OxidEsales\ExamplesModule\Greeting\Settings\GreetingSettingsInterface;
use OxidEsales\ExamplesModule\Greeting\Transput\SaveGreetingRequestInterface;
use OxidEsales\ExamplesModule\Tracker\Infrastructure\Repository\TrackerRepositoryInterface;

/**
 * @extendable-class
 *
 * This is a brand new (module own) controller which extends from the
 * shop frontend controller class.
 */
class GreetingController extends FrontendController
{
    /**
     * Current view template
     *
     * @var string
     * @SuppressWarnings("PHPMD.CamelCasePropertyName")
     */
    protected $_sThisTemplate = '@oe_examples_module/templates/greetingtemplate';

    public function __construct(
        private readonly GreetingSettingsInterface $greetingSettings,
        private readonly TrackerRepositoryInterface $trackerRepository,
        private readonly GreetingMessageServiceInterface $greetingService,
        private readonly SaveGreetingRequestInterface $saveGreetingRequest,
        private readonly UserRepositoryInterface $userRepository,
    ) {
        parent::__construct();
    }

    /**
     * Rendering method.
     *
     * @return mixed
     */
    public function render()
    {
        $template = parent::render();

        try {
            $activeUser = $this->userRepository->getActiveUser();
        } catch (UserNotLoggedIn $e) {
            $activeUser = null;
        }

        if (
            $activeUser instanceof UserInterface
            && !empty($activeUser->getId())
            && $this->greetingSettings->isPersonalGreetingMode()
        ) {
            $greeting = $activeUser->getPersonalGreeting();
            $tracker = $this->trackerRepository->getTrackerByUserId($activeUser->getId());
            $counter = $tracker->getCount();
        }

        $this->addTplParam(ModuleCore::OEEM_GREETING_TEMPLATE_VARNAME, $greeting ?? '');
        $this->addTplParam(ModuleCore::OEEM_COUNTER_TEMPLATE_VARNAME, $counter ?? 0);

        return $template;
    }

    /**
     * NOTE: every public method in the controller will become part of the public API.
     *       A controller public method can be called via browser by cl=<controllerkey>&fnc=<methodname>.
     *       Take care not to accidentally expose methods that should not be part of the API.
     *       Leave the business logic to the service layer.
     */
    public function updateGreeting(): void
    {
        if ($this->greetingSettings->isPersonalGreetingMode()) {
            $this->greetingService->saveGreetingForCurrentUser($this->saveGreetingRequest->getGreetingMessage());
        }
    }
}
