<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Greeting\Service;

use OxidEsales\Eshop\Application\Model\User as EshopModelUser;
use OxidEsales\EshopCommunity\Internal\Transition\Adapter\ShopAdapterInterface;
use OxidEsales\ExamplesModule\Core\Module as ModuleCore;
use OxidEsales\ExamplesModule\Extension\Model\User as ExamplesModelUser;
use OxidEsales\ExamplesModule\Greeting\Exception\UserNotLoggedIn;
use OxidEsales\ExamplesModule\Greeting\Infrastructure\Repository\GreetingRepositoryInterface;
use OxidEsales\ExamplesModule\Greeting\Settings\GreetingSettingsInterface;

readonly class GreetingMessageService implements GreetingMessageServiceInterface
{
    public function __construct(
        private GreetingSettingsInterface $greetingSettings,
        private ShopAdapterInterface $shopAdapter,
        private ?string $shopName,
        private GreetingRepositoryInterface $greetingRepository,
    ) {
    }

    public function getGeneralGreeting(): string
    {
        return sprintf($this->translate(ModuleCore::GENERAL_GREETING_LANGUAGE_CONST), $this->shopName);
    }

    public function getGreeting(?EshopModelUser $user = null): string
    {
        $result = ModuleCore::DEFAULT_PERSONAL_GREETING_LANGUAGE_CONST;

        if (GreetingSettingsInterface::GREETING_MODE_PERSONAL == $this->greetingSettings->getGreetingMode()) {
            $result = $this->getUserGreeting($user);
        }

        return $this->translate($result);
    }

    /**
     * @todo: investigate the unclear - it triggers the translation of user greeting but this makes not much sense
     * @todo: method most likely should be removed.
     */
    private function translate(string $toTranslate): string
    {
        return $toTranslate ? $this->shopAdapter->translateString($toTranslate) : '';
    }

    public function saveGreetingForCurrentUser(string $message): void
    {
        try {
            $this->greetingRepository->saveGreetingForActiveUser($message);
        } catch (UserNotLoggedIn $e) {
            // log exception if needed
        }
    }

    /**
     * @todo: can be simplified by correctly type hinting, also use-cases should be double-checked
     */
    private function getUserGreeting(?EshopModelUser $user = null): string
    {
        if (is_object($user)) {
            /** @var ExamplesModelUser $user */
            $result = $user->getPersonalGreeting();
        }

        return $result ?? '';
    }
}
