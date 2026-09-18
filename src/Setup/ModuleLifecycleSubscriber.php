<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Setup;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Event\BeforeModuleDeactivationEvent;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Event\FinalizingModuleActivationEvent;
use OxidEsales\ExamplesModule\Core\Module;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ModuleLifecycleSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FinalizingModuleActivationEvent::class => 'onActivate',
            BeforeModuleDeactivationEvent::class => 'onDeactivate',
        ];
    }

    public function onActivate(FinalizingModuleActivationEvent $event): void
    {
        if ($event->getModuleId() !== Module::MODULE_ID) {
            return;
        }

        $this->logger->info(sprintf('Module %s activated for shop %d', Module::MODULE_ID, $event->getShopId()));
    }

    public function onDeactivate(BeforeModuleDeactivationEvent $event): void
    {
        if ($event->getModuleId() !== Module::MODULE_ID) {
            return;
        }

        $this->logger->info(sprintf('Module %s deactivated for shop %d', Module::MODULE_ID, $event->getShopId()));
    }
}
