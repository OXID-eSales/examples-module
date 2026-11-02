<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Unit\Core;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Event\BeforeModuleDeactivationEvent;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Event\FinalizingModuleActivationEvent;
use OxidEsales\ExamplesModule\Core\Module;
use OxidEsales\ExamplesModule\Core\ModuleLifecycleSubscriber;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class ModuleLifecycleSubscriberTest extends TestCase
{
    #[Test]
    public function subscribesToModuleActivationAndDeactivationEvents(): void
    {
        $this->assertSame(
            [
                FinalizingModuleActivationEvent::class => 'onActivate',
                BeforeModuleDeactivationEvent::class => 'onDeactivate',
            ],
            ModuleLifecycleSubscriber::getSubscribedEvents()
        );
    }

    #[Test]
    public function activationIsHandledForThisModule(): void
    {
        $loggerSpy = $this->createMock(LoggerInterface::class);
        $loggerSpy->expects($this->once())
            ->method('info')
            ->with($this->stringContains(Module::MODULE_ID));

        $sut = $this->getSut(logger: $loggerSpy);

        $event = new FinalizingModuleActivationEvent(1, Module::MODULE_ID);
        $sut->onActivate($event);
    }

    #[Test]
    public function activationIsIgnoredForOtherModules(): void
    {
        $loggerSpy = $this->createMock(LoggerInterface::class);
        $loggerSpy->expects($this->never())
            ->method('info');

        $sut = $this->getSut(logger: $loggerSpy);

        $event = new FinalizingModuleActivationEvent(1, 'other_module');
        $sut->onActivate($event);
    }

    #[Test]
    public function deactivationIsHandledForThisModule(): void
    {
        $loggerSpy = $this->createMock(LoggerInterface::class);
        $loggerSpy->expects($this->once())
            ->method('info')
            ->with($this->stringContains(Module::MODULE_ID));

        $sut = $this->getSut(logger: $loggerSpy);

        $event = new BeforeModuleDeactivationEvent(1, Module::MODULE_ID);
        $sut->onDeactivate($event);
    }

    #[Test]
    public function deactivationIsIgnoredForOtherModules(): void
    {
        $loggerSpy = $this->createMock(LoggerInterface::class);
        $loggerSpy->expects($this->never())->method('info');

        $sut = $this->getSut(logger: $loggerSpy);

        $event = new BeforeModuleDeactivationEvent(1, 'other_module');
        $sut->onDeactivate($event);
    }

    private function getSut(
        ?LoggerInterface $logger = null,
    ): ModuleLifecycleSubscriber {
        return new ModuleLifecycleSubscriber(
            logger: $logger ?? $this->createStub(LoggerInterface::class),
        );
    }
}
